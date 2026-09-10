<?php

declare(strict_types=1);

namespace App\Routing;

use App\Controller\ReservationController;
use App\Controller\SalleController;
use FastRoute\RouteCollector;

function addRoutes(RouteCollector $routes): void
{
    $routes->get('/', [SalleController::class, 'index']);
    $routes->get('/salles', [SalleController::class, 'index']);
    $routes->get('/salles/create', [SalleController::class, 'create']);
    $routes->post('/salles', [SalleController::class, 'store']);
    $routes->get('/salles/{id:\d+}', [SalleController::class, 'show']);
    $routes->get('/salles/{id:\d+}/edit', [SalleController::class, 'edit']);
    $routes->post('/salles/{id:\d+}/edit', [SalleController::class, 'update']);

    $routes->get('/reservations', [ReservationController::class, 'index']);
    $routes->get('/reservations/create', [ReservationController::class, 'create']);
    $routes->post('/reservations', [ReservationController::class, 'store']);
    $routes->get('/reservations/{id:\d+}', [ReservationController::class, 'show']);
    $routes->post('/reservations/{id:\d+}/cancel', [ReservationController::class, 'cancel']);
}
