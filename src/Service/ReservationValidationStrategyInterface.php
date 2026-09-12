<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\CreerReservationDTO;
use App\Model\Salle;

interface ReservationValidationStrategyInterface
{
    public function validate(CreerReservationDTO $dto, Salle $salle): void;
}
