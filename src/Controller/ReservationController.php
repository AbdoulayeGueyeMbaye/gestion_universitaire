<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\CreerReservationDTOBuilder;
use App\Exception\ReservationIntrouvableException;
use App\Http\Response;
use App\Repository\ReservationRepositoryInterface;
use App\Repository\SalleRepositoryInterface;
use App\Service\AnnulerReservationService;
use App\Service\CreerReservationService;
use App\Validation\ReservationValidator;
use App\View\ViewRenderer;
use Throwable;

final class ReservationController
{
    public function __construct(
        private ReservationRepositoryInterface $reservations,
        private SalleRepositoryInterface $salles,
        private ReservationValidator $validator,
        private CreerReservationService $creation,
        private AnnulerReservationService $annulation,
        private ViewRenderer $views,
    ) {
    }

    public function index(?int $salleId = null): Response
    {
        return Response::html($this->views->render('reservation/index', [
            'title' => 'Reservations',
            'reservations' => $this->reservations->lister($salleId),
        ]));
    }

    public function show(int $id): Response
    {
        $reservation = $this->reservations->trouver($id);

        if ($reservation === null) {
            return $this->notFound();
        }

        return Response::html($this->views->render('reservation/show', [
            'title' => 'Reservation #' . $reservation->id,
            'reservation' => $reservation,
        ]));
    }

    public function create(): Response
    {
        return $this->form();
    }

    public function store(array $data): Response
    {
        $result = $this->validator->validate($data);

        if (!$result->isValid()) {
            return $this->form($data, $result->errors(), 422);
        }

        try {
            $dto = (new CreerReservationDTOBuilder())->fromArray($result->data())->build();
            $this->creation->execute($dto);
        } catch (Throwable $exception) {
            return $this->form($data, ['reservation' => [$exception->getMessage()]], 422);
        }

        return Response::redirect('/reservations');
    }

    public function cancel(int $id): Response
    {
        try {
            $this->annulation->execute($id);
        } catch (ReservationIntrouvableException $exception) {
            return $this->notFound();
        }

        return Response::redirect('/reservations');
    }

    private function form(array $data = [], array $errors = [], int $status = 200): Response
    {
        return Response::html($this->views->render('reservation/form', [
            'title' => 'Ajouter une reservation',
            'data' => $data,
            'errors' => $errors,
            'salles' => $this->salles->lister(),
        ]), $status);
    }

    private function notFound(): Response
    {
        return Response::html($this->views->render('error/404', ['title' => 'Page introuvable']), 404);
    }
}
