<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use App\Model\Reservation;
use App\Model\Salle;
use App\Repository\EloquentReservationRepository;
use App\Repository\EloquentSalleRepository;
use DateTimeImmutable;
use Illuminate\Database\Capsule\Manager;
use PHPUnit\Framework\TestCase;

final class RepositoryIntegrationTest extends TestCase
{
    private EloquentSalleRepository $salles;
    private EloquentReservationRepository $reservations;

    protected function setUp(): void
    {
        parent::setUp();

        if (!extension_loaded('pdo_mysql')) {
            self::markTestSkipped('L extension pdo_mysql est necessaire.');
        }

        /** @var Manager $capsule */
        $capsule = require dirname(__DIR__, 2) . '/config/database.php';

        try {
            $capsule->getConnection()->getPdo();
        } catch (\Throwable $exception) {
            self::markTestSkipped('La base MySQL de test est indisponible.');
        }

        require dirname(__DIR__, 2) . '/database/migrations/001_create_salles_table.php';
        require dirname(__DIR__, 2) . '/database/migrations/002_create_reservations_table.php';
        (new \CreateSallesTable())->up($capsule);
        (new \CreateReservationsTable())->up($capsule);

        $this->salles = new EloquentSalleRepository();
        $this->reservations = new EloquentReservationRepository();
    }

    public function testCreationSalleAvecEloquent(): void
    {
        $salle = $this->salles->enregistrer($this->newSalle());

        self::assertNotNull($salle->id);
    }

    public function testRelationSalleReservations(): void
    {
        $salle = $this->salles->enregistrer($this->newSalle());
        $reservation = $this->reservations->enregistrer($this->newReservation($salle->id));

        self::assertSame($salle->id, $reservation->salle->id);
    }

    public function testRechercheDeChevauchement(): void
    {
        $salle = $this->salles->enregistrer($this->newSalle());
        $debut = new DateTimeImmutable('2030-01-02 10:00:00');
        $fin = new DateTimeImmutable('2030-01-02 12:00:00');
        $this->reservations->enregistrer($this->newReservation($salle->id, $debut, $fin));

        $conflit = $this->reservations->rechercherConflit(
            $salle->id,
            new DateTimeImmutable('2030-01-02 11:00:00'),
            new DateTimeImmutable('2030-01-02 13:00:00'),
        );

        self::assertNotNull($conflit);
    }

    public function testAnnulationReservation(): void
    {
        $salle = $this->salles->enregistrer($this->newSalle());
        $reservation = $this->reservations->enregistrer($this->newReservation($salle->id));
        $annulee = $this->reservations->annuler($reservation);

        self::assertSame('annulee', $annulee->statut);
    }

    private function newSalle(): Salle
    {
        return new Salle([
            'nom' => 'Integration ' . uniqid(),
            'batiment' => 'Test',
            'capacite' => 20,
            'type' => 'cours',
            'active' => true,
        ]);
    }

    private function newReservation(int $salleId, ?DateTimeImmutable $debut = null, ?DateTimeImmutable $fin = null): Reservation
    {
        return new Reservation([
            'salle_id' => $salleId,
            'responsable' => 'Test Integration',
            'email' => 'test@example.com',
            'motif' => 'Test integration',
            'date_debut' => $debut ?? new DateTimeImmutable('2030-01-02 10:00:00'),
            'date_fin' => $fin ?? new DateTimeImmutable('2030-01-02 12:00:00'),
            'statut' => 'confirmee',
        ]);
    }
}
