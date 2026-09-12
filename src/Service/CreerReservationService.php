<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\CreerReservationDTO;
use App\Exception\SalleIndisponibleException;
use App\Model\Reservation;
use App\Repository\ReservationRepositoryInterface;
use App\Repository\SalleRepositoryInterface;

final class CreerReservationService
{
    public function __construct(
        private SalleRepositoryInterface $salles,
        private ReservationRepositoryInterface $reservations,
        private ReservationValidationStrategyInterface $validationStrategy,
    ) {
    }

    public function execute(CreerReservationDTO $dto): Reservation
    {
        $salle = $this->salles->trouver($dto->salleId);

        if ($salle === null) {
            throw new SalleIndisponibleException('La salle ne peut pas etre reservee.');
        }

        $this->validationStrategy->validate($dto, $salle);

        return $this->reservations->enregistrer(new Reservation([
            'salle_id' => $dto->salleId,
            'responsable' => $dto->responsable,
            'email' => $dto->email,
            'motif' => $dto->motif,
            'date_debut' => $dto->dateDebut,
            'date_fin' => $dto->dateFin,
            'statut' => 'confirmee',
        ]));
    }
}
