<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\CreerReservationDTO;
use App\Exception\SalleIndisponibleException;
use App\Model\Salle;
use App\Repository\ReservationRepositoryInterface;
use DateTimeImmutable;
use InvalidArgumentException;

final class StandardReservationValidationStrategy implements ReservationValidationStrategyInterface
{
    public function __construct(private ReservationRepositoryInterface $reservations)
    {
    }

    public function validate(CreerReservationDTO $dto, Salle $salle): void
    {
        if (!$salle->active) {
            throw new SalleIndisponibleException('La salle ne peut pas etre reservee.');
        }

        if ($dto->dateDebut >= $dto->dateFin) {
            throw new InvalidArgumentException('La date de debut doit preceder la date de fin.');
        }

        $duree = $dto->dateFin->getTimestamp() - $dto->dateDebut->getTimestamp();

        if ($duree > 4 * 60 * 60) {
            throw new InvalidArgumentException('Une reservation ne peut pas depasser quatre heures.');
        }

        if ($dto->dateDebut <= new DateTimeImmutable()) {
            throw new InvalidArgumentException('La reservation doit commencer dans le futur.');
        }

        if ($this->reservations->rechercherConflit($dto->salleId, $dto->dateDebut, $dto->dateFin) !== null) {
            throw new SalleIndisponibleException('La salle est indisponible pendant cette periode.');
        }
    }
}
