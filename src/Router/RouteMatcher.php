<?php

declare(strict_types=1);

namespace Onix\Router;

use Onix\Router\Route\MatchedRoute;

class RouteMatcher
{
    private RouteFactory $routeFactory;

    public function __construct(RouteFactory $routeFactory)
    {
        $this->routeFactory = $routeFactory;
    }

    /**
     * @param CompiledRouteCollection $compiledRouteCollection
     * @param string $path
     * @return MatchedRoute
     * @throws RouteNotFoundException
     */
    public function match(CompiledRouteCollection $compiledRouteCollection, string $path): MatchedRoute
    {
        foreach ($compiledRouteCollection as $compiledRoute) {
            $params = $compiledRoute->match($path);

            if ($params === null) {
                continue;
            }

            return $this->routeFactory->createMatchedRoute($compiledRoute->getRoute(), $params);
        }

        throw new RouteNotFoundException("Route not found for given path: ${path}");
    }
}
