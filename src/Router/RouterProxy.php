<?php

declare(strict_types=1);

namespace Onix\Router;

class RouterProxy implements RouteCollectorInterface
{
    private RouteCollectorInterface $routeCollector;
    private array $attributes;

    public function __construct(RouteCollectorInterface $routeCollector, array $attributes)
    {
        $this->routeCollector = $routeCollector;
        $this->attributes = $attributes;
    }

    public function route(string $method, string $template, string $action, array $attributes = []): void
    {
        $this->routeCollector->route($method, $template, $action, $this->getMergedAttributes($attributes));
    }

    public function get(string $template, string $action, array $attributes = []): void
    {
        $this->routeCollector->get($template, $action, $this->getMergedAttributes($attributes));
    }

    public function post(string $template, string $action, array $attributes = []): void
    {
        $this->routeCollector->post($template, $action, $this->getMergedAttributes($attributes));
    }

    public function patch(string $template, string $action, array $attributes = []): void
    {
        $this->routeCollector->patch($template, $action, $this->getMergedAttributes($attributes));
    }

    public function put(string $template, string $action, array $attributes = []): void
    {
        $this->routeCollector->put($template, $action, $this->getMergedAttributes($attributes));
    }

    public function delete(string $template, string $action, array $attributes = []): void
    {
        $this->routeCollector->delete($template, $action, $this->getMergedAttributes($attributes));
    }

    public function head(string $template, string $action, array $attributes = []): void
    {
        $this->routeCollector->head($template, $action, $this->getMergedAttributes($attributes));
    }

    public function trace(string $template, string $action, array $attributes = []): void
    {
        $this->routeCollector->trace($template, $action, $this->getMergedAttributes($attributes));
    }

    public function setNotFoundRoute(string $action, array $attributes = []): void
    {
        $this->routeCollector->setNotFoundRoute($action, $this->getMergedAttributes($attributes));
    }

    public function middlewares(array $middlewares, callable $routes): void
    {
        $this->routeCollector->middlewares($middlewares, $routes);
    }

    private function getMergedAttributes(array $attributes)
    {
        return array_merge($attributes, $this->attributes);
    }
}
