<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Validation\ReservationValidator;
use App\Validation\SalleValidator;
use PHPUnit\Framework\TestCase;

final class ValidationTest extends TestCase
{
    public function testEmailInvalide(): void
    {
        $result = (new ReservationValidator())->validate($this->reservationData(['email' => 'invalide']));

        self::assertFalse($result->isValid());
        self::assertArrayHasKey('email', $result->errors());
    }

    public function testResponsableVide(): void
    {
        $result = (new ReservationValidator())->validate($this->reservationData(['responsable' => '']));

        self::assertArrayHasKey('responsable', $result->errors());
    }

    public function testCapaciteNegative(): void
    {
        $result = (new SalleValidator())->validate([
            'nom' => 'Salle A',
            'batiment' => 'Batiment A',
            'capacite' => -1,
            'type' => 'cours',
            'active' => true,
        ]);

        self::assertArrayHasKey('capacite', $result->errors());
    }

    public function testTypeInconnu(): void
    {
        $result = (new SalleValidator())->validate([
            'nom' => 'Salle A',
            'batiment' => 'Batiment A',
            'capacite' => 20,
            'type' => 'inconnu',
            'active' => true,
        ]);

        self::assertArrayHasKey('type', $result->errors());
    }

    public function testDateIncorrecte(): void
    {
        $result = (new ReservationValidator())->validate($this->reservationData([
            'date_debut' => 'date incorrecte',
        ]));

        self::assertArrayHasKey('date_debut', $result->errors());
    }

    private function reservationData(array $overrides = []): array
    {
        return array_merge([
            'salle_id' => 1,
            'responsable' => 'Awa Ndiaye',
            'email' => 'awa@example.com',
            'motif' => 'Cours de PHP',
            'date_debut' => '2030-01-02 10:00:00',
            'date_fin' => '2030-01-02 12:00:00',
        ], $overrides);
    }
}
