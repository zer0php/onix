<?php

declare(strict_types=1);

namespace Onix\Router;

use Generator;
use IteratorAggregate;
use Onix\Router\Route\Route;

class RouteCollection implements IteratorAggregate
{
    private array $routes;
    private RouteFactory $routeFactory;

    public function __construct(array $routes, RouteFactory $routeFactory)
    {
        $this->routes = $routes;
        $this->routeFactory = $routeFactory;
    }

    /**
     * @return Generator|Route[]
     */
    public function getIterator(): Generator
    {
        foreach ($this->routes as $routeArray) {
            [$template, $action, $attributes] = $routeArray;
            yield $this->routeFactory->createRoute($template, $action, $attributes);
        }
    }
}
