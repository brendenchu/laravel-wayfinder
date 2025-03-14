<?php

namespace Brendenchu\Wayfinder\Concerns;

use Illuminate\Contracts\Auth\Authenticatable;

trait HasSearchable
{
    /**
     * Returns the search configuration for this searchable class.
     *
     * @return array
     */
    public static function searchable(): array
    {
        return [
            'allowedParams' => static::getSearchFields(),
            'optionFields' => static::getOptionFields(),
            'validationRules' => static::getSearchValidationRules(),
            'validationMessages' => static::getSearchValidationMessages(),
            'validationAttributes' => static::getSearchValidationAttributes(),
            'authorize' => static::authorizeSearch(),
            'view' => static::getSearchView(),
        ];
    }

    /**
     * Get the search fields for this searchable class.
     *
     * @return array
     */
    private static function getSearchFields(): array
    {
        return static::$searchFields ?? [];
    }

    /**
     * Get option fields for the search form.
     *
     * @return array
     */
    private static function getOptionFields(): array
    {
        return static::$optionFields ?? [];
    }

    /**
     * Get the search validation rules.
     *
     * @return array
     */
    private static function getSearchValidationRules(): array
    {
        return static::$searchValidationRules ?? [];
    }

    /**
     * Get the search validation messages.
     *
     * @return array
     */
    private static function getSearchValidationMessages(): array
    {
        return static::$searchValidationMessages ?? [];
    }


    /**
     * Get the search validation attributes.
     *
     * @return array
     */
    private static function getSearchValidationAttributes(): array
    {
        return static::$searchValidationAttributes ?? [];
    }

    /**
     * Get the view to render search results.
     *
     * @return string|null
     */
    private static function getSearchView(): ?string
    {
        return static::$searchView ?? null;
    }

    /**
     * Determine if the user is authorized to search this searchable class.
     *
     * @param Authenticatable|null $user
     * @return bool
     */
    public static function authorizeSearch(Authenticatable $user = null): bool
    {
        if (method_exists(static::class, 'searchAuthorization')) {
            return static::searchAuthorization($user);
        }

        return true;
    }
}
