<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\CreerSalleDTOBuilder;
use App\Http\Response;
use App\Model\Salle;
use App\Repository\SalleRepositoryInterface;
use App\Validation\SalleValidator;
use App\View\ViewRenderer;

final class SalleController
{
    public function __construct(
        private SalleRepositoryInterface $salles,
        private SalleValidator $validator,
        private ViewRenderer $views,
    ) {
    }

    public function index(): Response
    {
        return Response::html($this->views->render('salle/index', [
            'title' => 'Salles',
            'salles' => $this->salles->lister(),
        ]));
    }

    public function show(int $id): Response
    {
        $salle = $this->salles->trouver($id);

        if ($salle === null) {
            return $this->notFound();
        }

        return Response::html($this->views->render('salle/show', [
            'title' => $salle->nom,
            'salle' => $salle,
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

        $dto = (new CreerSalleDTOBuilder())->fromArray($result->data())->build();
        $this->salles->enregistrer(new Salle([
            'nom' => $dto->nom,
            'batiment' => $dto->batiment,
            'capacite' => $dto->capacite,
            'type' => $dto->type,
            'active' => $dto->active,
        ]));

        return Response::redirect('/salles');
    }

    public function edit(int $id): Response
    {
        $salle = $this->salles->trouver($id);

        if ($salle === null) {
            return $this->notFound();
        }

        return $this->form($salle->toArray(), [], 200, $salle->id);
    }

    public function update(int $id, array $data): Response
    {
        $salle = $this->salles->trouver($id);

        if ($salle === null) {
            return $this->notFound();
        }

        $result = $this->validator->validate($data);

        if (!$result->isValid()) {
            return $this->form($data, $result->errors(), 422, $id);
        }

        $dto = (new CreerSalleDTOBuilder())->fromArray($result->data())->build();
        $salle->fill([
            'nom' => $dto->nom,
            'batiment' => $dto->batiment,
            'capacite' => $dto->capacite,
            'type' => $dto->type,
            'active' => $dto->active,
        ]);
        $this->salles->enregistrer($salle);

        return Response::redirect('/salles/' . $id);
    }

    private function form(array $data = [], array $errors = [], int $status = 200, ?int $id = null): Response
    {
        return Response::html($this->views->render('salle/form', [
            'title' => $id === null ? 'Ajouter une salle' : 'Modifier une salle',
            'data' => $data,
            'errors' => $errors,
            'id' => $id,
        ]), $status);
    }

    private function notFound(): Response
    {
        return Response::html($this->views->render('error/404', ['title' => 'Page introuvable']), 404);
    }
}
