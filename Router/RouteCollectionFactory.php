<?php

declare(strict_types=1);

namespace Onix\Router;

class RouteCollectionFactory
{
    private RouteFactory $routeFactory;
    private RouteCompiler $routeCompiler;

    public function __construct(RouteFactory $routeFactory, RouteCompiler $routeCompiler)
    {
        $this->routeFactory = $routeFactory;
        $this->routeCompiler = $routeCompiler;
    }

    public function createRouteCollection(array $routes): RouteCollection
    {
        return new RouteCollection($routes, $this->routeFactory);
    }

    public function createCompiledRouteCollection(RouteCollection $routeCollection): CompiledRouteCollection
    {
        return new CompiledRouteCollection($routeCollection, $this->routeCompiler);
    }
}
