<?php

declare(strict_types=1);

namespace App\Validation;

use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as RespectValidator;

final class SalleValidator implements ValidatorInterface
{
    public function validate(array $data): ValidationResult
    {
        $rules = [
            'nom' => RespectValidator::stringType()->notEmpty()->length(2, 100),
            'batiment' => RespectValidator::stringType()->notEmpty()->length(2, 100),
            'capacite' => RespectValidator::intVal()->between(1, 1000),
            'type' => RespectValidator::in([
                'cours',
                'informatique',
                'laboratoire',
                'amphitheatre',
                'reunion',
            ]),
            'active' => RespectValidator::boolType(),
        ];

        return $this->validateFields($data, $rules);
    }

    private function validateFields(array $data, array $rules): ValidationResult
    {
        $acceptedData = [];
        $errors = [];

        foreach ($rules as $field => $rule) {
            $value = $data[$field] ?? null;

            try {
                $rule->assert($value);
                $acceptedData[$field] = $value;
            } catch (NestedValidationException $exception) {
                $errors[$field] = $exception->getMessages();
            }
        }

        return new ValidationResult($acceptedData, $errors);
    }
}
