<?php

declare(strict_types=1);

use Illuminate\Database\Capsule\Manager as Capsule;

require dirname(__DIR__) . '/vendor/autoload.php';

/** @var Capsule $capsule */
$capsule = require dirname(__DIR__) . '/config/database.php';

try {
    $capsule->getConnection()->getPdo();
    $migration = require dirname(__DIR__) . '/database/migrations/001_create_tables.php';
    $migration($capsule);

    fwrite(STDOUT, "Migration terminee.\n");
} catch (Throwable $exception) {
    fwrite(STDERR, "Echec de la connexion ou de la migration : {$exception->getMessage()}\n");
    exit(1);
}
