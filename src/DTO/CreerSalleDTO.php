<?php

declare(strict_types=1);

namespace App\DTO;

use InvalidArgumentException;

final readonly class CreerSalleDTO
{
    public function __construct(
        public string $nom,
        public string $batiment,
        public int $capacite,
        public string $type,
        public bool $active,
    ) {
    }

    public static function fromArray(array $data): self
    {
        $active = filter_var($data['active'] ?? null, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

        if ($active === null) {
            throw new InvalidArgumentException('La valeur active doit etre un booleen.');
        }

        return new self(
            nom: (string) $data['nom'],
            batiment: (string) $data['batiment'],
            capacite: (int) $data['capacite'],
            type: (string) $data['type'],
            active: $active,
        );
    }
}
