<?php

declare(strict_types=1);

namespace App\Validation;

use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as RespectValidator;

final class ReservationValidator implements ValidatorInterface
{
    public function validate(array $data): ValidationResult
    {
        $rules = [
            'salle_id' => RespectValidator::intVal()->positive(),
            'responsable' => RespectValidator::stringType()->notEmpty()->length(2, 120),
            'email' => RespectValidator::email(),
            'motif' => RespectValidator::stringType()->notEmpty()->length(5, 255),
            'date_debut' => RespectValidator::dateTime(),
            'date_fin' => RespectValidator::dateTime(),
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
