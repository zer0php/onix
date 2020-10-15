<?php

declare(strict_types=1);

namespace Onix\Router;

use Generator;
use IteratorAggregate;
use Onix\Router\Route\CompiledRoute;

class CompiledRouteCollection implements IteratorAggregate
{
    private RouteCollection $routeCollection;
    private RouteCompiler $routeCompiler;

    public function __construct(RouteCollection $routeCollection, RouteCompiler $routeCompiler)
    {
        $this->routeCollection = $routeCollection;
        $this->routeCompiler = $routeCompiler;
    }

    /**
     * @return Generator|CompiledRoute[]
     */
    public function getIterator(): Generator
    {
        foreach ($this->routeCollection as $route) {
            yield $this->routeCompiler->compile($route);
        }
    }
}
