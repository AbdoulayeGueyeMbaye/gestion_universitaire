<?php

declare(strict_types=1);

use App\Model\Salle;
use Illuminate\Database\Capsule\Manager as Capsule;

require dirname(__DIR__) . '/vendor/autoload.php';

/** @var Capsule $capsule */
$capsule = require dirname(__DIR__) . '/config/database.php';

$salles = [
    [
        'nom' => 'Amphitheatre A',
        'batiment' => 'Batiment principal',
        'capacite' => 250,
        'type' => 'amphitheatre',
    ],
    [
        'nom' => 'Salle B12',
        'batiment' => 'Batiment B',
        'capacite' => 40,
        'type' => 'cours',
    ],
    [
        'nom' => 'Laboratoire Chimie',
        'batiment' => 'Batiment des sciences',
        'capacite' => 24,
        'type' => 'laboratoire',
    ],
    [
        'nom' => 'Salle Informatique 1',
        'batiment' => 'Batiment informatique',
        'capacite' => 30,
        'type' => 'informatique',
    ],
    [
        'nom' => 'Salle de reunion',
        'batiment' => 'Administration',
        'capacite' => 12,
        'type' => 'reunion',
    ],
];

try {
    $capsule->getConnection()->getPdo();

    foreach ($salles as $salle) {
        Salle::updateOrCreate(
            [
                'nom' => $salle['nom'],
                'batiment' => $salle['batiment'],
            ],
            [
                'capacite' => $salle['capacite'],
                'type' => $salle['type'],
                'active' => true,
            ],
        );
    }

    fwrite(STDOUT, count($salles) . " salles initialisees.\n");
} catch (Throwable $exception) {
    fwrite(STDERR, "Echec du seeding : {$exception->getMessage()}\n");
    exit(1);
}
