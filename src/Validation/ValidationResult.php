<?php

declare(strict_types=1);

namespace App\Validation;

final class ValidationResult
{
    public function __construct(
        private array $acceptedData,
        private array $errors,
    ) {
    }

    public function isValid(): bool
    {
        return $this->errors === [];
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function data(): array
    {
        return $this->acceptedData;
    }
}
