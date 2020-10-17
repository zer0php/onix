<?php

declare(strict_types=1);

namespace Onix\Router;

use Onix\Router\Route\MatchedRoute;

class RouteFinder
{
    private Router $router;
    private RouteMatcher $routeMatcher;

    public function __construct(
        Router $router,
        RouteMatcher $routeMatcher
    ) {
        $this->router = $router;
        $this->routeMatcher = $routeMatcher;
    }

    /**
     * @param string $method
     * @param string $path
     * @return MatchedRoute
     * @throws RouteNotFoundException
     */
    public function getMatchedRouteByMethodAndPath(string $method, string $path): MatchedRoute
    {
        $compiledRouteCollection = $this->router->getRoutesByMethod($method);

        try {
            return $this->routeMatcher->match(
                $compiledRouteCollection,
                $path
            );
        } catch (RouteNotFoundException $exception) {
            $notFoundRoute = $this->router->getNotFoundRoute();

            if ($notFoundRoute !== null) {
                return $notFoundRoute;
            }

            throw $exception;
        }
    }
}
