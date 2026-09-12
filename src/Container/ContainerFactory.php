<?php

declare(strict_types=1);

namespace App\Container;

use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;

final class ContainerFactory
{
    public static function create(string $projectPath): ContainerInterface
    {
        $builder = new ContainerBuilder();
        $builder->addDefinitions($projectPath . '/config/container.php');

        return $builder->build();
    }
}
