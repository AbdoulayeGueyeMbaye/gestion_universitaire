<?php

declare(strict_types=1);

namespace App\Routing;

use App\Http\Response;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use InvalidArgumentException;
use Psr\Container\ContainerInterface;
use function FastRoute\simpleDispatcher;

final class Router
{
    public function __construct(
        private ContainerInterface $container,
        private Dispatcher $dispatcher,
    ) {
    }

    public static function fromRoutes(ContainerInterface $container, string $routesFile): self
    {
        $routeDefinition = require $routesFile;

        if (!is_callable($routeDefinition)) {
            throw new InvalidArgumentException('Le fichier de routes doit retourner un callable.');
        }

        $dispatcher = simpleDispatcher(static function (RouteCollector $routes) use ($routeDefinition): void {
            $routeDefinition($routes);
        });

        return new self($container, $dispatcher);
    }

    public function dispatch(string $method, string $uri, array $input = []): Response
    {
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';
        $result = $this->dispatcher->dispatch(strtoupper($method), $path);

        switch ($result[0]) {
            case Dispatcher::NOT_FOUND:
                return Response::html('<h1>Page introuvable</h1>', 404);
            case Dispatcher::METHOD_NOT_ALLOWED:
                return new Response(
                    '<h1>Methode non autorisee</h1>',
                    405,
                    [
                        'Allow' => implode(', ', $result[1]),
                        'Content-Type' => 'text/html; charset=UTF-8',
                    ],
                );
            case Dispatcher::FOUND:
                return $this->callHandler($result[1], $result[2], strtoupper($method), $input);
            default:
                throw new InvalidArgumentException('Resultat FastRoute inconnu.');
        }
    }

    private function callHandler(mixed $handler, array $parameters, string $method, array $input): Response
    {
        if (!is_array($handler) || count($handler) !== 2) {
            throw new InvalidArgumentException('Le handler doit contenir une classe et une methode.');
        }

        [$controllerClass, $action] = $handler;
        $controller = $this->container->get($controllerClass);
        $arguments = array_values($parameters);

        if ($method === 'POST') {
            $arguments[] = $input;
        }

        $response = $controller->{$action}(...$arguments);

        if (!$response instanceof Response) {
            throw new InvalidArgumentException('Une action doit retourner une Response.');
        }

        return $response;
    }
}
