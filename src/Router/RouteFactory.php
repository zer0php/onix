<?php

declare(strict_types=1);

namespace Onix\Router;

use Onix\Router\Route\CompiledRoute;
use Onix\Router\Route\MatchedRoute;
use Onix\Router\Route\Route;

class RouteFactory
{
    public function createRoute(string $template, string $action, array $attributes = []): Route
    {
        return new Route($template, $action, $attributes);
    }

    public function createCompiledRoute(Route $route, string $pattern): CompiledRoute
    {
        return new CompiledRoute($route, $pattern);
    }

    public function createMatchedRoute(Route $route, array $params): MatchedRoute
    {
        return new MatchedRoute($route, $params);
    }
}
