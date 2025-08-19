<?php

declare(strict_types=1);

namespace App\RequestValidators;

use App\Validation\Validators;
use App\Contracts\RequestValidatorInterface;
use App\Exceptions\ValidationException;
use PDO;

class RegisterUserRequestValidator implements RequestValidatorInterface
{

    public function __construct(private readonly PDO $pdo) {}

    public function validate(array $data): array
    {

        $v = new Validators($data);

        $v->label('confirmPassword', 'Confirm Password');
        $v->label('password', 'Password');

        $v->rule('required', ['name', 'email', 'password', 'confirmPassword']);
        $v->rule('email', 'email');
        $v->rule('equals', 'confirmPassword', 'password');

        $v->rule(function ($field, $value, $params, $all) use ($v) {

            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
            $stmt->execute(['email' => $value]);

            if ($stmt->fetchColumn()) {

                $v->addCustomError($field, 'Email Address is already taken');
                return false;
            }
            return true;
        }, 'email');

        if (!$v->validate()) {
            throw new ValidationException($v->errors());
        }


        return $data;
    }
}
