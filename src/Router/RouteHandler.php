<?php

declare(strict_types=1);

namespace Onix\Router;

use Onix\Container\Container;
use Onix\Container\NotFoundException;
use Onix\Http\RequestHandlerInterface;
use Onix\Http\ResponseInterface;
use Onix\Http\ServerRequest;
use Onix\Router\Route\MatchedRoute;

class RouteHandler implements RequestHandlerInterface
{
    private Container $container;
    private RouteFinder $routeFinder;

    public function __construct(Container $container, RouteFinder $routeFinder)
    {
        $this->container = $container;
        $this->routeFinder = $routeFinder;
    }

    /**
     * @param ServerRequest $request
     * @return ResponseInterface
     * @throws NotFoundException
     * @throws RouteNotFoundException
     */
    public function handle(ServerRequest $request): ResponseInterface
    {
        $matchedRoute = $this->getMatchedRouteFromRequest($request);
        $action = $matchedRoute->getRoute()->getAction();
        $attributes = $this->getAttributes($matchedRoute);
        $requestWithAttributes = $request->withAttributes($attributes);

        return $this->getHandler($action)->handle($requestWithAttributes);
    }

    /**
     * @param string $handlerClass
     * @return RequestHandlerInterface
     * @throws NotFoundException
     */
    private function getHandler(string $handlerClass): RequestHandlerInterface
    {
        return $this->container->get($handlerClass);
    }

    private function getAttributes(MatchedRoute $matchedRoute): array
    {
        $route = $matchedRoute->getRoute();
        return array_merge(
            $route->getAttributes(),
            $matchedRoute->getParams()
        );
    }

    /**
     * @param ServerRequest $request
     * @return MatchedRoute
     * @throws RouteNotFoundException
     */
    private function getMatchedRouteFromRequest(ServerRequest $request): MatchedRoute
    {
        return $this->routeFinder->getMatchedRouteByMethodAndPath(
            $request->getMethod(),
            $request->getPath()
        );
    }
}
