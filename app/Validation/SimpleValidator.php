<?php

declare(strict_types=1);

namespace App\Validation;

class SimpleValidatorValidator
{
    protected  $data = [];
    protected  $rules = [];
    protected  $errors = [];

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function rule($rule, $fields, ...$params)
    {
        if (!is_array($fields)) {
            $fields = [$fields];
        }

        foreach ($fields as $field) {
            $this->rules[$field][] = [
                'rule' => $rule,
                'param' => $params,
            ];
        }

        return $this;
    }


    public function validate()
    {
        foreach ($this->rules as $field => $rules) {
            $value = isset($this->data[$field]) ? $this->data[$field] : null;

            foreach ($rules as $ruleData) {
                $rule = $ruleData['rule'];
                $params = $ruleData['param'];

                if ($rule === 'required' && empty($value)) {
                    $this->addError($field, "$field is required.");
                }
                if ($rule === 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addError($field, "$field must be valid email address");
                }

                if ($rule === 'equals') {
                    $otherField = isset($params[0]) ? $params[0] : '';
                    if ($value !== ($this->data[$otherField] ?? null)) {
                        $this->addError($field, "$field Must match $otherField");
                    }
                }

                if (is_callable($rule)) {
                    $result = call_user_func($rule, $field, $value, $params, $this->data);
                    if (!$result) {
                        $this->addError($field, "$field is invalid");
                    }
                }
            }
        }

        return empty($this->errors);
    }


    public function addError($field, $message)
    {
        if (!isset($this->errors[$field])) {
            $this->errors[$field] = [];
        }

        $this->errors[$field] = $message;
    }

    public function error()
    {
        return $this->errors;
    }
}
