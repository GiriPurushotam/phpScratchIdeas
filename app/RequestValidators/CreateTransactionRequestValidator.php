<?php

declare(strict_types=1);

namespace App\RequestValidators;

use App\Contracts\RequestValidatorInterface;
use App\DataObjects\RegisterTransactionData;
use App\Exceptions\ValidationException;
use App\Validation\Validators;

class CreateTransactionRequestValidator implements RequestValidatorInterface
{
    public function validate(array $data): RegisterTransactionData
    {
        $v = new Validators($data);

        $v->rule('required', ['category_id', 'description', 'date', 'amount']);
        $v->rule('integer', 'category_id');
        $v->rule('min', 'category_id', 1);
        $v->rule('lengthMax', 'description', 255);
        $v->rule('dateFormat', 'date', 'Y-m-d');
        $v->rule('numeric', 'amount');
        $v->rule('min', 'amount', 0.01);

        if (! $v->validate()) {
            throw new ValidationException($v->errors());
        }

        return new RegisterTransactionData(
            categoryId: (int) $data['category_id'],
            description: $data['description'],
            date: new \DateTimeImmutable($data['date']),
            amount: (float) $data['amount']
        );
    }
}
