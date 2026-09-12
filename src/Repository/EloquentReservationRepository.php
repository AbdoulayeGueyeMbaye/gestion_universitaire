<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\Reservation;
use DateTimeInterface;

final class EloquentReservationRepository implements ReservationRepositoryInterface
{
    public function lister(?int $salleId = null): array
    {
        $query = Reservation::query()->orderBy('date_debut');

        if ($salleId !== null) {
            $query->where('salle_id', $salleId);
        }

        return $query->get()->all();
    }

    public function trouver(int $id): ?Reservation
    {
        return Reservation::query()->find($id);
    }

    public function rechercherConflit(
        int $salleId,
        DateTimeInterface $dateDebut,
        DateTimeInterface $dateFin,
    ): ?Reservation {
        return Reservation::query()
            ->where('salle_id', $salleId)
            ->where('statut', 'confirmee')
            ->where('date_debut', '<', $dateFin)
            ->where('date_fin', '>', $dateDebut)
            ->first();
    }

    public function enregistrer(Reservation $reservation): Reservation
    {
        $reservation->save();

        return $reservation;
    }

    public function annuler(Reservation $reservation): Reservation
    {
        $reservation->statut = 'annulee';
        $reservation->save();

        return $reservation;
    }
}
