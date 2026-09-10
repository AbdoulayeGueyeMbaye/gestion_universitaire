<?php

declare(strict_types=1);

namespace App\Service;

use App\Exception\ReservationIntrouvableException;
use App\Model\Reservation;
use App\Repository\ReservationRepositoryInterface;

final class AnnulerReservationService
{
    public function __construct(private ReservationRepositoryInterface $reservations)
    {
    }

    public function execute(int $reservationId): Reservation
    {
        $reservation = $this->reservations->trouver($reservationId);

        if ($reservation === null) {
            throw new ReservationIntrouvableException('La reservation est introuvable.');
        }

        return $this->reservations->annuler($reservation);
    }
}
