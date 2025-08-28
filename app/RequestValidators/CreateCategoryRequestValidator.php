<?php

declare(strict_types=1);

namespace App\RequestValidators;

use App\Contracts\RequestValidatorInterface;
use App\Exceptions\ValidationException;
use App\Validation\Validators;

class CreateCategoryRequestValidator implements RequestValidatorInterface
{
    public function validate(array $data): array
    {

        $v = new Validators($data);

        $v->rule('required', 'name');
        $v->rule('lengthMax', 'name', 50);

        if (!$v->validate()) {
            throw new ValidationException($v->errors());
        }

        return $data;
    }
}
