<?php

declare(strict_types=1);

namespace App\RequestValidators;

use App\Exceptions\ValidationException;
use App\Contracts\RequestValidatorInterface;
use App\Validation\Validators;

class LoginUserRequestValidator implements RequestValidatorInterface
{

    public function validate(array $data): array
    {
        $v = new Validators($data);

        $v->rule('required', ['email', 'password']);
        $v->rule('email', 'email');

        if (!$v->validate()) {
            throw new ValidationException($v->errors());
        }

        return $data;
    }
}
