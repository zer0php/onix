<?php

declare(strict_types=1);

namespace Onix\Router;

use Onix\Router\Route\MatchedRoute;

class Router implements RouteCollectorInterface
{
    private RouteCollectionFactory $routeCollectionFactory;
    private RouteFactory $routeFactory;

    private array $routeMap = [];
    private ?array $notFoundRouteArray = null;

    public function __construct(RouteCollectionFactory $routeCollectionFactory, RouteFactory $routeFactory)
    {
        $this->routeCollectionFactory = $routeCollectionFactory;
        $this->routeFactory = $routeFactory;
    }

    public function route(string $method, string $template, string $action, array $attributes = []): void
    {
        $this->routeMap[$method][] = [$template, $action, $attributes];
    }

    public function get(string $template, string $action, array $attributes = []): void
    {
        $this->route('GET', $template, $action, $attributes);
    }

    public function post(string $template, string $action, array $attributes = []): void
    {
        $this->route('POST', $template, $action, $attributes);
    }

    public function patch(string $template, string $action, array $attributes = []): void
    {
        $this->route('PATCH', $template, $action, $attributes);
    }

    public function put(string $template, string $action, array $attributes = []): void
    {
        $this->route('PUT', $template, $action, $attributes);
    }

    public function delete(string $template, string $action, array $attributes = []): void
    {
        $this->route('DELETE', $template, $action, $attributes);
    }

    public function head(string $template, string $action, array $attributes = []): void
    {
        $this->route('HEAD', $template, $action, $attributes);
    }

    public function trace(string $template, string $action, array $attributes = []): void
    {
        $this->route('TRACE', $template, $action, $attributes);
    }

    public function middlewares(array $middlewares, callable $routes): void
    {
        $routes(new RouterProxy($this, ['_middlewares' => $middlewares]));
    }

    public function setNotFoundRoute(string $action, array $attributes = []): void
    {
        $this->notFoundRouteArray = [$action, $attributes];
    }

    public function getNotFoundRoute(): ?MatchedRoute
    {
        if ($this->notFoundRouteArray !== null) {
            [$notFoundRouteAction, $notFoundRouteAttributes] = $this->notFoundRouteArray;
            $route = $this->routeFactory->createRoute('/', $notFoundRouteAction, $notFoundRouteAttributes);

            return $this->routeFactory->createMatchedRoute($route, []);
        }

        return null;
    }

    public function getRoutesByMethod(string $method): CompiledRouteCollection
    {
        $routes = $this->routeMap[$method] ?? [];

        $routeCollection = $this->routeCollectionFactory->createRouteCollection($routes);

        return $this->routeCollectionFactory->createCompiledRouteCollection($routeCollection);
    }
}
