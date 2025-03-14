<?php

namespace Brendenchu\Wayfinder\Support;

use Brendenchu\Wayfinder\Contracts\WithSearchable;

class Searchable
{
    /**
     * Allowed search parameters
     *
     * @var array
     */
    public $allowedParams;

    /**
     * Option fields for the search form
     *
     * @var array|null
     */
    public $optionFields;

    /**
     * Validation rules for search parameters
     *
     * @var array|null
     */
    public $validationRules;

    /**
     * Validation messages for search parameters
     *
     * @var array|null
     */
    public $validationMessages;

    /**
     * Validation attributes for search parameters
     *
     * @var array|null
     */
    public $validationAttributes;

    /**
     * View to render search results
     *
     * @var string|null
     */
    public $view;

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
        switch ($name) {
            case 'allowedParams':
                return $this->allowedParams;
            case 'optionFields':
                return $this->optionFields;
            case 'validationRules':
                return $this->validationRules;
            case 'validationMessages':
                return $this->validationMessages;
            case 'validationAttributes':
                return $this->validationAttributes;
            case 'view':
                return $this->view;
            default:
                return null;
        }
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
