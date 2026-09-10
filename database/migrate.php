<?php

declare(strict_types=1);

use Illuminate\Database\Capsule\Manager as Capsule;

require dirname(__DIR__) . '/vendor/autoload.php';

/** @var Capsule $capsule */
$capsule = require dirname(__DIR__) . '/config/database.php';

try {
    $capsule->getConnection()->getPdo();
    $direction = $argv[1] ?? 'up';

    if (!in_array($direction, ['up', 'down'], true)) {
        throw new InvalidArgumentException('La direction doit être "up" ou "down".');
    }

    require dirname(__DIR__) . '/database/migrations/001_create_salles_table.php';
    require dirname(__DIR__) . '/database/migrations/002_create_reservations_table.php';

    $migrations = [
        new CreateSallesTable(),
        new CreateReservationsTable(),
    ];

    if ($direction === 'down') {
        $migrations = array_reverse($migrations);
    }

    foreach ($migrations as $migration) {
        $migration->{$direction}($capsule);
    }

    fwrite(STDOUT, "Migration terminee.\n");
} catch (Throwable $exception) {
    fwrite(STDERR, "Echec de la connexion ou de la migration : {$exception->getMessage()}\n");
    exit(1);
}
