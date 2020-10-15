<?php

declare(strict_types=1);

namespace Onix\Router\Route;

class MatchedRoute
{
    private Route $route;
    private array $params;

    public function __construct(Route $route, array $params)
    {
        $this->route = $route;
        $this->params = $params;
    }

    public function getRoute(): Route
    {
        return $this->route;
    }

    public function getParams(): array
    {
        return $this->params;
    }
}
