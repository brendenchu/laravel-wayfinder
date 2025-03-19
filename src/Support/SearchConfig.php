<?php

namespace Brendenchu\Wayfinder\Support;

use Brendenchu\Wayfinder\Contracts\WithSearchable;

class SearchConfig
{
    /**
     * Allowed search parameters
     *
     * @var array
     */
    public array $allowedParams;

    /**
     * Option fields for the search form
     *
     * @var array|null
     */
    public ?array $optionFields;

    /**
     * Validation rules for search parameters
     *
     * @var array|null
     */
    public ?array $validationRules;

    /**
     * Validation messages for search parameters
     *
     * @var array|null
     */
    public ?array $validationMessages;

    /**
     * Validation attributes for search parameters
     *
     * @var array|null
     */
    public ?array $validationAttributes;

    /**
     * View to render search results
     *
     * @var string|null
     */
    public ?string $view;

    /**
     * Create a new Searchable instance
     *
     * @param array $allowedParams
     * @param array $optionFields
     * @param array $validationRules
     * @param array $validationMessages
     * @param array $validationAttributes
     * @param string|null $view
     */
    private function __construct(
        array   $allowedParams,
        array   $optionFields,
        array   $validationRules,
        array   $validationMessages,
        array   $validationAttributes,
        ?string $view = null
    )
    {
        $this->allowedParams = $allowedParams;
        $this->optionFields = $optionFields;
        $this->validationRules = $validationRules;
        $this->validationMessages = $validationMessages;
        $this->validationAttributes = $validationAttributes;
        $this->view = $view;
    }

    /**
     * Create a Searchable instance from a class that uses the HasSearchable trait
     *
     * @param WithSearchable $searchableClass
     * @return self
     */
    public static function fromClass(WithSearchable $searchableClass): self
    {
        $config = $searchableClass::searchable();

        return new self(
            $config['allowedParams'],
            $config['optionFields'] ?? [],
            $config['validationRules'] ?? [],
            $config['validationMessages'] ?? [],
            $config['validationAttributes'] ?? [],
            $config['view'] ?? null,
        );
    }

    /**
     * Create a Searchable instance from an array
     *
     * @param array $array
     * @return self
     */
    public static function fromArray(array $array): self
    {
        return new self(
            $array['allowedParams'],
            $array['optionFields'] ?? [],
            $array['validationRules'] ?? [],
            $array['validationMessages'] ?? [],
            $array['validationAttributes'] ?? [],
            $array['view'] ?? null,
        );
    }

    /**
     * Create a Searchable instance from JSON
     *
     * @param string $json
     * @return self
     */
    public static function fromJson(string $json): self
    {
        return self::fromArray(json_decode($json, true));
    }

    /**
     * Convert the Searchable instance to an array
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'allowedParams' => $this->allowedParams,
            'optionFields' => $this->optionFields,
            'validationRules' => $this->validationRules,
            'validationMessages' => $this->validationMessages,
            'validationAttributes' => $this->validationAttributes,
            'view' => $this->view,
        ];
    }

    /**
     * Convert the Searchable instance to JSON
     *
     * @return string
     */
    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    /**
     * Get a property of the Searchable instance
     *
     * @return array|string|null
     */
    public function __get(string $name)
    {
        return match ($name) {
            'allowedParams' => $this->allowedParams,
            'optionFields' => $this->optionFields,
            'validationRules' => $this->validationRules,
            'validationMessages' => $this->validationMessages,
            'validationAttributes' => $this->validationAttributes,
            'view' => $this->view,
            default => null,
        };
    }

    /**
     * Check if a property is set
     *
     * @param string $name
     * @return bool
     */
    public function __isset(string $name): bool
    {
        return in_array($name, [
            'allowedParams', 'optionFields', 'validationRules', 'validationMessages', 'validationAttributes', 'view'
        ]);
    }

}
