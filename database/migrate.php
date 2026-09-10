<?php

declare(strict_types=1);

use App\Application;

require dirname(__DIR__) . '/vendor/autoload.php';

$capsule = require dirname(__DIR__) . '/config/database.php';
$command = match ($argv[1] ?? 'up') {
    'up' => 'migrate:up',
    'down' => 'migrate:down',
    default => $argv[1],
};

exit((new Application($capsule))->run([$argv[0], $command]));
