<?php

declare(strict_types=1);

use App\Controller\ReservationController;
use App\Controller\SalleController;
use App\Routing\Router;
use App\Repository\EloquentReservationRepository;
use App\Repository\EloquentSalleRepository;
use App\Repository\ReservationRepositoryInterface;
use App\Repository\SalleRepositoryInterface;
use App\Service\ReservationValidationStrategyInterface;
use App\Service\StandardReservationValidationStrategy;
use App\View\ViewRenderer;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Illuminate\Database\Capsule\Manager as Capsule;
use function DI\autowire;
use function DI\factory;
use function FastRoute\simpleDispatcher;

return [
    Capsule::class => factory(static function (): Capsule {
        return require dirname(__DIR__) . '/config/database.php';
    }),
    SalleRepositoryInterface::class => autowire(EloquentSalleRepository::class),
    ReservationRepositoryInterface::class => autowire(EloquentReservationRepository::class),
    ReservationValidationStrategyInterface::class => autowire(StandardReservationValidationStrategy::class),
    ViewRenderer::class => factory(static function (): ViewRenderer {
        return new ViewRenderer(dirname(__DIR__) . '/templates');
    }),
    Dispatcher::class => factory(static function (): Dispatcher {
        require dirname(__DIR__) . '/routes/web.php';

        return simpleDispatcher(static function (RouteCollector $routes): void {
            \App\Routing\addRoutes($routes);
        });
    }),
    Router::class => autowire(),
    SalleController::class => autowire(),
    ReservationController::class => autowire(),
];
