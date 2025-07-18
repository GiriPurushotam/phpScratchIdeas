<?php

declare(strict_types=1);

namespace App\Validation;

class Validators
{
    protected array $data = [];
    protected array $rules = [];
    protected array $errors = [];

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function rule(string|\Closure $rule, string|array $fields, ...$params): self
    {
        foreach ((array)$fields as $field) {
            $this->rules[$field][] = compact('rule', 'params');
        }

        return $this;
    }

    public function validate(): bool
    {
        foreach ($this->rules as $field => $rules) {
            $value = $this->data[$field] ?? null;
            foreach ($rules as $ruleData) {
                $rule = $ruleData['rule'];
                $params = $ruleData['params'];

                if ($rule instanceof \Closure) {
                    $result = $rule($field, $value, $params, $this->data);
                    if (!$result) {
                        $this->addErrors($field, "$field is invalid");
                    }

                    continue;
                }

                if ($rule === 'required' && empty($value)) {
                    $this->addErrors($field, "$field is required");
                }

                if ($rule === 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addErrors($field, "$field must be a valid email address");
                }

                if ($rule === 'equals') {
                    $otherField = $params[0] ?? '';
                    if ($value !== ($this->data[$otherField] ?? null)) {
                        $this->addErrors($field, "$field must match $otherField");
                    }
                }
            }
        }

        return empty($this->errors);
    }


    protected function addErrors(string $field, string $message): void
    {
        $this->errors[$field][] = $message;
    }


    public function errors(): array
    {
        return $this->errors;
    }
}
