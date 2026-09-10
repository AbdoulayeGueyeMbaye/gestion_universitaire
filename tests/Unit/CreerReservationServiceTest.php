<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\DTO\CreerReservationDTOBuilder;
use App\Exception\SalleIndisponibleException;
use App\Model\Reservation;
use App\Model\Salle;
use App\Repository\ReservationRepositoryInterface;
use App\Repository\SalleRepositoryInterface;
use App\Service\CreerReservationService;
use App\Service\StandardReservationValidationStrategy;
use DateTimeInterface;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class CreerReservationServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        require dirname(__DIR__, 2) . '/config/database.php';
    }

    public function testReservationValide(): void
    {
        [$service, $repository] = $this->service();

        $reservation = $service->execute($this->dto());

        self::assertSame('confirmee', $reservation->statut);
        self::assertSame($reservation, $repository->saved);
    }

    public function testSalleInexistante(): void
    {
        [$service] = $this->service(null, null, false);

        $this->expectException(SalleIndisponibleException::class);
        $service->execute($this->dto());
    }

    public function testSalleInactive(): void
    {
        [$service] = $this->service(new Salle(['active' => false]));

        $this->expectException(SalleIndisponibleException::class);
        $service->execute($this->dto());
    }

    public function testFinAvantDebut(): void
    {
        [$service] = $this->service();

        $this->expectException(InvalidArgumentException::class);
        $service->execute($this->dto('2030-01-02 12:00:00', '2030-01-02 10:00:00'));
    }

    public function testDureeSuperieureAQuatreHeures(): void
    {
        [$service] = $this->service();

        $this->expectException(InvalidArgumentException::class);
        $service->execute($this->dto('2030-01-02 08:00:00', '2030-01-02 14:00:00'));
    }

    public function testDatePassee(): void
    {
        [$service] = $this->service();

        $this->expectException(InvalidArgumentException::class);
        $service->execute($this->dto('2020-01-02 10:00:00', '2020-01-02 12:00:00'));
    }

    public function testConflit(): void
    {
        [$service] = $this->service(new Salle(['active' => true]), new Reservation());

        $this->expectException(SalleIndisponibleException::class);
        $service->execute($this->dto());
    }

    public function testReservationVoisineSansChevauchement(): void
    {
        [$service] = $this->service();

        $reservation = $service->execute($this->dto('2030-01-02 12:00:00', '2030-01-02 14:00:00'));

        self::assertSame('confirmee', $reservation->statut);
    }

    private function service(?Salle $salle = null, ?Reservation $conflict = null, bool $salleExiste = true): array
    {
        if ($salleExiste) {
            $salle ??= new Salle(['active' => true]);
        }
        $salleRepository = new class($salle) implements SalleRepositoryInterface {
            public function __construct(private ?Salle $salle)
            {
            }

            public function lister(): array { return []; }
            public function trouver(int $id): ?Salle { return $this->salle; }
            public function enregistrer(Salle $salle): Salle { return $salle; }
        };
        $reservationRepository = new class($conflict) implements ReservationRepositoryInterface {
            public ?Reservation $saved = null;

            public function __construct(private ?Reservation $conflict)
            {
            }

            public function lister(?int $salleId = null): array { return []; }
            public function trouver(int $id): ?Reservation { return null; }
            public function rechercherConflit(int $salleId, DateTimeInterface $dateDebut, DateTimeInterface $dateFin): ?Reservation { return $this->conflict; }
            public function enregistrer(Reservation $reservation): Reservation { return $this->saved = $reservation; }
            public function annuler(Reservation $reservation): Reservation { return $reservation; }
        };
        $strategy = new StandardReservationValidationStrategy($reservationRepository);

        return [new CreerReservationService($salleRepository, $reservationRepository, $strategy), $reservationRepository];
    }

    private function dto(string $debut = '2030-01-02 10:00:00', string $fin = '2030-01-02 12:00:00')
    {
        return (new CreerReservationDTOBuilder())
            ->fromArray([
                'salle_id' => 1,
                'responsable' => 'Awa Ndiaye',
                'email' => 'awa@example.com',
                'motif' => 'Cours de PHP',
                'date_debut' => $debut,
                'date_fin' => $fin,
            ])
            ->build();
    }
}
