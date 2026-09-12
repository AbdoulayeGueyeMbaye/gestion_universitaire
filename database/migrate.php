<?php

declare(strict_types=1);

use App\Application;
use App\Container\ContainerFactory;

require dirname(__DIR__) . '/vendor/autoload.php';

$container = ContainerFactory::create(dirname(__DIR__));
$command = match ($argv[1] ?? 'up') {
    'up' => 'migrate:up',
    'down' => 'migrate:down',
    default => $argv[1],
};

exit($container->get(Application::class)->run([$argv[0], $command]));
