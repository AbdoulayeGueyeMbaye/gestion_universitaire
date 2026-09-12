<?php

declare(strict_types=1);

namespace App\Routing;

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
        require $routesFile;

        $dispatcher = simpleDispatcher(static function (RouteCollector $routes): void {
            \App\Routing\addRoutes($routes);
        });

        return new self($container, $dispatcher);
    }

    public function dispatch(string $method, string $uri, array $input = []): array
    {
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';
        $result = $this->dispatcher->dispatch(strtoupper($method), $path);

        switch ($result[0]) {
            case Dispatcher::NOT_FOUND:
                return $this->htmlResponse('<h1>Page introuvable</h1>', 404);
            case Dispatcher::METHOD_NOT_ALLOWED:
                return [
                    'body' => '<h1>Methode non autorisee</h1>',
                    'status' => 405,
                    'headers' => [
                        'Allow' => implode(', ', $result[1]),
                        'Content-Type' => 'text/html; charset=UTF-8',
                    ],
                ];
            case Dispatcher::FOUND:
                return $this->callHandler($result[1], $result[2], strtoupper($method), $input);
            default:
                throw new InvalidArgumentException('Resultat FastRoute inconnu.');
        }
    }

    private function callHandler(mixed $handler, array $parameters, string $method, array $input): array
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

        if (!isset($response['status'], $response['headers'], $response['body'])) {
            throw new InvalidArgumentException('Une action doit retourner une reponse HTTP standard.');
        }

        return $response;
    }

    private function htmlResponse(string $body, int $status): array
    {
        return [
            'body' => $body,
            'status' => $status,
            'headers' => ['Content-Type' => 'text/html; charset=UTF-8'],
        ];
    }
}
