<?php

namespace Brendenchu\Wayfinder;

use Brendenchu\Wayfinder\Contracts\WithSearchable;
use Brendenchu\Wayfinder\Support\Searchable;
use Brendenchu\Wayfinder\Support\SearchResponse;
use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\URL;
use InvalidArgumentException;

class Wayfinder
{
    /**
     * Create a search response object for a searchable class.
     *
     * @param array $params
     * @param string $searchableClass
     * @param Closure|null $callback
     * @return SearchResponse
     * @throws InvalidArgumentException
     */
    public function search(array $params, string $searchableClass, ?Closure $callback = null): SearchResponse
    {
        if (!$this->canSearch($searchableClass)) {
            throw new InvalidArgumentException("Class {$searchableClass} does not implement WithSearchable interface");
        }

        $config = $this->config($searchableClass);

        $allowedParams = array_intersect_key($params, array_flip($config->allowedParams));
        $query = $searchableClass::query();

        if ($callback === null) {
            if (method_exists($searchableClass, 'searchableQuery')) {
                $callback = $searchableClass::searchableQuery();
            }
        }

        // Use provided search callback or fall back to default implementation
        $results = $callback
            ? call_user_func($callback, $query, $allowedParams)
            : $this->defaultQuery($query, $allowedParams);

        return SearchResponse::generate(
            $allowedParams,
            $results,
            $config->optionFields
        );
    }

    /**
     * Get the search configuration for a searchable class.
     *
     * @param string $searchableClass
     * @return Searchable
     * @throws InvalidArgumentException
     */
    public function config(string $searchableClass): Searchable
    {
        if (!$this->canSearch($searchableClass)) {
            throw new InvalidArgumentException("Class {$searchableClass} does not implement WithSearchable interface");
        }
        return Searchable::fromClass(new $searchableClass);
    }

    /**
     * Check if a searchable class implements the WithSearchable interface.
     *
     * @param string $searchableClass
     * @return bool
     */
    public function canSearch(string $searchableClass): bool
    {
        return class_exists($searchableClass) &&
            in_array(WithSearchable::class, class_implements($searchableClass) ?: []);
    }

    /**
     * Get all searchables from the configuration.
     *
     * @return array
     */
    public function searchables(): array
    {
        return array_filter($this->getSearchableMapping(), function ($class) {
            return $this->canSearch($class);
        });
    }

    /**
     * Get URL for a searchable class with optional parameters.
     *
     * @param string $searchableName
     * @param array $params
     * @return string
     */
    public function url(string $searchableName, array $params = []): string
    {
        $prefix = rtrim(config('wayfinder.route_prefix'), '/');

        return URL::to("{$prefix}/{$searchableName}" .
            ($params ? '?' . http_build_query($params) : ''));
    }

    /**
     * Default search implementation.
     *
     * @param Builder $query
     * @param array $params
     * @return LengthAwarePaginator
     */
    protected function defaultQuery(Builder $query, array $params): LengthAwarePaginator
    {
        foreach ($params as $field => $value) {
            if (empty($value) && $value !== 0 && $value !== '0') {
                continue;
            }

            if (is_array($value)) {
                $query->whereIn($field, $value);
            } else if (str_ends_with($field, '_min')) {
                $realField = substr($field, 0, -4);
                $query->where($realField, '>=', $value);
            } else if (str_ends_with($field, '_max')) {
                $realField = substr($field, 0, -4);
                $query->where($realField, '<=', $value);
            } else if (str_ends_with($field, '_like')) {
                $realField = substr($field, 0, -5);
                $query->where($realField, 'like', "%{$value}%");
            } else if (is_string($value)) {
                $query->where($field, 'like', "%{$value}%");
            } else {
                $query->where($field, '=', $value);
            }
        }

        return $query->paginate(config('wayfinder.per_page'));
    }

    /**
     * Get the mapping of route names to searchable classes.
     *
     * @return array
     */
    protected function getSearchableMapping(): array
    {
        return config('wayfinder.searchables');
    }
}
