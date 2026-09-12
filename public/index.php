<?php

declare(strict_types=1);

use App\Application;
use App\Container\ContainerFactory;

require dirname(__DIR__) . '/vendor/autoload.php';

$container = ContainerFactory::create(dirname(__DIR__));

$container->get(Application::class)->run();
