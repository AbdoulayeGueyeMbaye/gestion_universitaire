<?php

declare(strict_types=1);

namespace App\DTO;

use DateTimeImmutable;

final readonly class CreerReservationDTO
{
    public function __construct(
        public int $salleId,
        public string $responsable,
        public string $email,
        public string $motif,
        public DateTimeImmutable $dateDebut,
        public DateTimeImmutable $dateFin,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            salleId: (int) $data['salle_id'],
            responsable: (string) $data['responsable'],
            email: (string) $data['email'],
            motif: (string) $data['motif'],
            dateDebut: new DateTimeImmutable((string) $data['date_debut']),
            dateFin: new DateTimeImmutable((string) $data['date_fin']),
        );
    }
}
