<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\Reservation;
use DateTimeInterface;

interface ReservationRepositoryInterface
{
    /** @return list<Reservation> */
    public function lister(?int $salleId = null): array;

    public function trouver(int $id): ?Reservation;

    public function rechercherConflit(
        int $salleId,
        DateTimeInterface $dateDebut,
        DateTimeInterface $dateFin,
    ): ?Reservation;

    public function enregistrer(Reservation $reservation): Reservation;

    public function annuler(Reservation $reservation): Reservation;
}
