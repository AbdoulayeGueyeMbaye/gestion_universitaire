<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\CreerSalleDTOBuilder;
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

    public function index(): array
    {
        return $this->html($this->views->render('salle/index', [
            'title' => 'Salles',
            'salles' => $this->salles->lister(),
        ]));
    }

    public function show(int $id): array
    {
        $salle = $this->salles->trouver($id);

        if ($salle === null) {
            return $this->notFound();
        }

        return $this->html($this->views->render('salle/show', [
            'title' => $salle->nom,
            'salle' => $salle,
        ]));
    }

    public function create(): array
    {
        return $this->form();
    }

    public function store(array $data): array
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

        return $this->redirect('/salles');
    }

    public function edit(int $id): array
    {
        $salle = $this->salles->trouver($id);

        if ($salle === null) {
            return $this->notFound();
        }

        return $this->form($salle->toArray(), [], 200, $salle->id);
    }

    public function update(int $id, array $data): array
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

        return $this->redirect('/salles/' . $id);
    }

    private function form(array $data = [], array $errors = [], int $status = 200, ?int $id = null): array
    {
        return $this->html($this->views->render('salle/form', [
            'title' => $id === null ? 'Ajouter une salle' : 'Modifier une salle',
            'data' => $data,
            'errors' => $errors,
            'id' => $id,
        ]), $status);
    }

    private function notFound(): array
    {
        return $this->html($this->views->render('error/404', ['title' => 'Page introuvable']), 404);
    }

    private function html(string $body, int $status = 200): array
    {
        return [
            'body' => $body,
            'status' => $status,
            'headers' => ['Content-Type' => 'text/html; charset=UTF-8'],
        ];
    }

    private function redirect(string $location): array
    {
        return ['body' => '', 'status' => 302, 'headers' => ['Location' => $location]];
    }
}
