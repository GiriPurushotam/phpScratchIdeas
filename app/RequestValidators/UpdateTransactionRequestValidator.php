<?php

declare(strict_types=1);

namespace App\RequestValidators;

use App\Contracts\RequestValidatorInterface;
use App\DataObjects\RegisterTransactionData;
use App\Validation\Validators;

class UpdateTransactionRequestValidator implements RequestValidatorInterface
{
    public function validate(array $data): RegisterTransactionData
    {
        $v = new Validators($data);

        $v->rule('required', ['description', 'amount', 'date', 'category_id', 'id']);

        $v->rule('lengthMax', 'description', 255);
        $v->rule('numeric', 'amount');
        $v->rule('min', 'amount', 0.01);
        $v->rule('dateFormat', 'date', 'Y-m-d');
        $v->rule('integer', 'category_id');
        $v->rule('min', 'category_id', 1);
        $v->rule('integer', 'id');

        if (! $v->validate()) {
            throw new \App\Exceptions\ValidationException($v->errors());
        }

        return new RegisterTransactionData(
            description: $data['description'],
            amount: (float) $data['amount'],
            date: new \DateTimeImmutable($data['date']),
            categoryId: $data['category_id']
        );
    }
}
