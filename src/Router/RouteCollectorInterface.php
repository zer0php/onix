<?php

declare(strict_types=1);

namespace Onix\Router;

interface RouteCollectorInterface
{
    public function route(string $method, string $template, string $action, array $attributes = []): void;
    public function get(string $template, string $action, array $attributes = []): void;
    public function post(string $template, string $action, array $attributes = []): void;
    public function patch(string $template, string $action, array $attributes = []): void;
    public function put(string $template, string $action, array $attributes = []): void;
    public function delete(string $template, string $action, array $attributes = []): void;
    public function head(string $template, string $action, array $attributes = []): void;
    public function trace(string $template, string $action, array $attributes = []): void;
    public function setNotFoundRoute(string $action, array $attributes = []): void;
    public function middlewares(array $middlewares, callable $routes): void;
}
