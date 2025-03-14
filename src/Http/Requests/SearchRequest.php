<?php

namespace Brendenchu\Wayfinder\Http\Requests;

use Brendenchu\Wayfinder\Facades\Wayfinder;
use Brendenchu\Wayfinder\Support\Searchable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SearchRequest extends FormRequest
{
    /**
     * Searchable class being searched
     *
     * @var string
     */
    protected $searchableClass;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $this->determineSearchableClass();

        if (!$this->searchableClass || !method_exists($this->searchableClass, 'searchable')) {
            return [];
        }

        $config = Wayfinder::config($this->searchableClass);
        $rules = [];

        // Generate rules based on allowed params and field types
        foreach ($config->allowedParams as $param) {
            $rules[$param] = $this->generateRulesForField($param, $config);
        }

        return $rules;
    }

    /**
     * Determine which searchable class is being searched
     */
    protected function determineSearchableClass()
    {
        $searchableName = $this->route('searchable') ?? config('wayfinder.default_searchable');

        if (!$searchableName) {
            return;
        }

        $searchableMap = config('wayfinder.searchables');
        $this->searchableClass = $searchableMap[$searchableName] ?? null;
    }

    /**
     * Generate validation rules for a specific field
     *
     * @param string $field
     * @param Searchable $config
     * @return array
     */
    protected function generateRulesForField(string $field, Searchable $config): array
    {
        $rules = ['nullable'];

        // Check if specific validation rules exist for this field
        if (isset($config->validationRules[$field])) {
            $customRules = $config->validationRules[$field];
            if (!is_array($customRules)) {
                $customRules = implode('|', $customRules);
            }
            return array_values(array_unique(array_merge($rules, $customRules)));
        }

        // Handle fields with options (should match available options)
        if (isset($config->optionFields[$field])) {
            $options = $config->optionFields[$field];

            // Handle different option configurations
            if (is_array($options) && isset($options['source'])) {
                $source = $options['source'];
                $multiple = $options['multiple'] ?? false;

                if ($multiple) {
                    $rules[] = 'array';
                }

                if (is_array($source)) {
                    // For static arrays, validate against available keys
                    if ($multiple) {
                        $rules[] = 'array';
                    }
                    $rules[] = Rule::in(array_keys($source));
                } elseif (is_string($source) && class_exists($source)) {
                    // For searchable classes, validate against existing IDs
                    $table = with(new $source)->getTable();
                    if ($multiple) {
                        $rules[] = 'array';
                    }
                    $rules[] = 'exists:' . $table . ',id';
                }
            } elseif (is_array($options)) {
                // Simple array of options (old format)
                $rules[] = Rule::in(array_keys($options));
            } elseif (is_string($options) && class_exists($options)) {
                // Searchable class (old format)
                $rules[] = 'exists:' . with(new $options)->getTable() . ',id';
            }
        } else {
            // Guess type based on field name
            if (Str::endsWith($field, ['_min', '_max'])) {
                $rules[] = 'numeric';
            } elseif (Str::endsWith($field, ['_at', '_date'])) {
                $rules[] = 'date';
            } elseif (Str::endsWith($field, '_id')) {
                $rules[] = 'integer';
            } elseif (Str::contains($field, ['email'])) {
                $rules[] = 'email';
            }
        }

        return $rules;
    }

    /**
     * Get the sanitized search params
     *
     * @return array
     */
    public function searchParams(): array
    {
        $this->determineSearchableClass();

        if (!$this->searchableClass || !method_exists($this->searchableClass, 'searchable')) {
            return [];
        }

        $config = Wayfinder::config($this->searchableClass);
        return $this->only($config->allowedParams);
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        $this->determineSearchableClass();

        if (!$this->searchableClass || !method_exists($this->searchableClass, 'searchable')) {
            return [];
        }

        $config = Wayfinder::config($this->searchableClass);

        return $config->validationMessages ?? [];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes(): array
    {
        $this->determineSearchableClass();

        if (!$this->searchableClass || !method_exists($this->searchableClass, 'searchable')) {
            return [];
        }

        $config = Wayfinder::config($this->searchableClass);

        return $config->validationAttributes ?? [];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        $this->determineSearchableClass();

        if (!$this->searchableClass) {
            return false;
        }

        if (method_exists($this->searchableClass, 'authorizeSearch')) {
            return $this->searchableClass::authorizeSearch($this->user());
        }

        return true;
    }
}
