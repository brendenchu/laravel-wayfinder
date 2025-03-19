<?php

namespace Brendenchu\Wayfinder\Support;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;

class SearchResponse
{
    /**
     * The search parameters with their values
     *
     * @var array
     */
    protected array $params;

    /**
     * The search results (can be paginated or collection)
     *
     * @var mixed
     */
    protected mixed $results;

    /**
     * Available options for form fields (dropdowns, checkboxes, radio buttons, etc.)
     *
     * @var array
     */
    protected array $options;

    /**
     * Create a new SearchResponse instance
     *
     * @param array $params
     * @param mixed|null $results
     * @param array $options
     */
    private function __construct(array $params = [], mixed $results = null, array $options = [])
    {
        $this->params = $params;
        $this->results = $results;
        $this->options = $options;
    }

    /**
     * Create a SearchResponse instance from parameters and results
     *
     * @param array $params
     * @param mixed $results
     * @param array $optionFields Field names with their option sources
     * @return self
     */
    public static function generate(
        array $params,
        mixed $results,
        array $optionFields = []
    ): self
    {
        // Get options for form fields
        $options = self::getFieldOptions($optionFields);

        return new self($params, $results, $options);
    }

    /**
     * Get options for form fields (dropdowns, checkboxes, radio buttons, etc.)
     *
     * @param array $optionFields
     * @return array
     */
    private static function getFieldOptions(array $optionFields): array
    {
        $options = [];

        foreach ($optionFields as $field => $source) {
            // Handle configuration with metadata
            if (is_array($source) && isset($source['source'])) {
                $optionSource = $source['source'];
                $labelKey = $source['labelKey'] ?? 'name';
                $valueKey = $source['valueKey'] ?? 'id';
                $type = $source['type'] ?? 'select'; // select, checkbox, radio, etc.

                $optionValues = self::resolveOptionSource($optionSource, $labelKey, $valueKey);

                $options[$field] = [
                    'values' => $optionValues,
                    'type' => $type,
                    'multiple' => $source['multiple'] ?? false,
                    'empty_option' => $source['empty_option'] ?? null,
                ];
            } else {
                // Simple source definition (backwards compatibility)
                $options[$field] = [
                    'values' => self::resolveOptionSource($source),
                    'type' => 'select',
                    'multiple' => false,
                    'empty_option' => null,
                ];
            }
        }

        return $options;
    }

    /**
     * Resolve option source to an array of options
     *
     * @param mixed $source
     * @param string $labelKey
     * @param string $valueKey
     * @return array
     */
    private static function resolveOptionSource(mixed $source, string $labelKey = 'name', string $valueKey = 'id'): array
    {
        if (is_callable($source)) {
            // If source is a callback, call it to get options
            return call_user_func($source);
        } else if (is_string($source) && class_exists($source)) {
            // If source is a searchable class, get all items
            return $source::all()->pluck($labelKey, $valueKey)->toArray();
        } else if (is_array($source)) {
            // If source is already an array, use it directly
            return $source;
        }

        return [];
    }

    /**
     * Convert the SearchResponse instance to an array
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'params' => $this->params,
            'results' => $this->results,
            'options' => $this->options,
        ];
    }

    /**
     * Get a specific parameter
     *
     * @param string $name
     * @param mixed|null $default
     * @return mixed
     */
    public function get(string $name, mixed $default = null): mixed
    {
        return Arr::get($this->params, $name, $default);
    }

    /**
     * Prepare the dropdown menu options for a specific field
     *
     * @param string $field
     * @return array
     */
    public function menu(string $field): array
    {
        $values = $this->getOptionValues($field);
        $emptyOption = $this->getEmptyOption($field, 'All');
        return ['' => $emptyOption] + $values;
    }

    /**
     * Check if there are any results
     *
     * @return bool
     */
    public function hasResults(): bool
    {
        if ($this->results instanceof Collection) {
            return $this->results->isNotEmpty();
        }

        if ($this->results instanceof LengthAwarePaginator) {
            return $this->results->total() > 0;
        }

        return !empty($this->results);
    }

    /**
     * Get the total number of results
     *
     * @return int
     */
    public function getResultsCount(): int
    {
        if ($this->results instanceof Collection) {
            return $this->results->count();
        }

        if ($this->results instanceof LengthAwarePaginator) {
            return $this->results->total();
        }

        if (is_array($this->results)) {
            return count($this->results);
        }

        return 0;
    }

    /**
     * Get option configuration for a specific field
     *
     * @param string $field
     * @return array
     */
    public function getOptionsForField(string $field): array
    {
        return $this->options[$field] ?? [];
    }

    /**
     * Get option values for a specific field
     *
     * @param string $field
     * @return array
     */
    public function getOptionValues(string $field): array
    {
        return $this->options[$field]['values'] ?? [];
    }

    /**
     * Get the input type for a field with options
     *
     * @param string $field
     * @return string
     */
    public function getFieldType(string $field): string
    {
        return $this->options[$field]['type'] ?? 'select';
    }

    /**
     * Check if a field allows multiple selections
     *
     * @param string $field
     * @return bool
     */
    public function isMultipleField(string $field): bool
    {
        return $this->options[$field]['multiple'] ?? false;
    }

    /**
     * Get empty option text for a field
     *
     * @param string $field
     * @param string|null $default
     * @return string|null
     */
    public function getEmptyOption(string $field, ?string $default = null): ?string
    {
        return $this->options[$field]['empty_option'] ?? $default;
    }

    /**
     * Check if a field has options
     *
     * @param string $field
     * @return bool
     */
    public function hasOptionsForField(string $field): bool
    {
        return isset($this->options[$field]['values']) && !empty($this->options[$field]['values']);
    }

    /**
     * Magic getter for accessing properties directly
     *
     * @param string $name
     * @return mixed
     */
    public function __get(string $name)
    {
        return match ($name) {
            'params' => $this->params,
            'results' => $this->results,
            'options' => $this->options,
            default => null,
        };
    }

    /**
     * Check if a property exists
     *
     * @param string $name
     * @return bool
     */
    public function __isset(string $name): bool
    {
        return in_array($name, ['params', 'results', 'options']);
    }
}
