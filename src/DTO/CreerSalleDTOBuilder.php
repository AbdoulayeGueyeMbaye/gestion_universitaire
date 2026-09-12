<?php

declare(strict_types=1);

namespace App\DTO;

use InvalidArgumentException;

final class CreerSalleDTOBuilder
{
    private ?string $nom = null;
    private ?string $batiment = null;
    private ?int $capacite = null;
    private ?string $type = null;
    private ?bool $active = null;

    public function fromArray(array $data): self
    {
        $active = filter_var($data['active'] ?? null, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

        if ($active === null) {
            throw new InvalidArgumentException('La valeur active doit etre un booleen.');
        }

        return $this
            ->withNom((string) $data['nom'])
            ->withBatiment((string) $data['batiment'])
            ->withCapacite((int) $data['capacite'])
            ->withType((string) $data['type'])
            ->withActive($active);
    }

    public function withNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function withBatiment(string $batiment): self
    {
        $this->batiment = $batiment;

        return $this;
    }

    public function withCapacite(int $capacite): self
    {
        $this->capacite = $capacite;

        return $this;
    }

    public function withType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function withActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function build(): CreerSalleDTO
    {
        if ($this->nom === null || $this->batiment === null || $this->capacite === null || $this->type === null || $this->active === null) {
            throw new InvalidArgumentException('Tous les champs de la salle sont requis.');
        }

        return new CreerSalleDTO(
            nom: $this->nom,
            batiment: $this->batiment,
            capacite: $this->capacite,
            type: $this->type,
            active: $this->active,
        );
    }
}
