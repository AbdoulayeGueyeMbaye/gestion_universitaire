<?php

declare(strict_types=1);

namespace App\DTO;

use DateTimeImmutable;
use InvalidArgumentException;

final class CreerReservationDTOBuilder
{
    private ?int $salleId = null;
    private ?string $responsable = null;
    private ?string $email = null;
    private ?string $motif = null;
    private ?DateTimeImmutable $dateDebut = null;
    private ?DateTimeImmutable $dateFin = null;

    public function fromArray(array $data): self
    {
        return $this
            ->withSalleId((int) $data['salle_id'])
            ->withResponsable((string) $data['responsable'])
            ->withEmail((string) $data['email'])
            ->withMotif((string) $data['motif'])
            ->withDateDebut(new DateTimeImmutable((string) $data['date_debut']))
            ->withDateFin(new DateTimeImmutable((string) $data['date_fin']));
    }

    public function withSalleId(int $salleId): self
    {
        $this->salleId = $salleId;

        return $this;
    }

    public function withResponsable(string $responsable): self
    {
        $this->responsable = $responsable;

        return $this;
    }

    public function withEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function withMotif(string $motif): self
    {
        $this->motif = $motif;

        return $this;
    }

    public function withDateDebut(DateTimeImmutable $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function withDateFin(DateTimeImmutable $dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function build(): CreerReservationDTO
    {
        if ($this->salleId === null || $this->responsable === null || $this->email === null || $this->motif === null || $this->dateDebut === null || $this->dateFin === null) {
            throw new InvalidArgumentException('Tous les champs de la reservation sont requis.');
        }

        return new CreerReservationDTO(
            salleId: $this->salleId,
            responsable: $this->responsable,
            email: $this->email,
            motif: $this->motif,
            dateDebut: $this->dateDebut,
            dateFin: $this->dateFin,
        );
    }
}
