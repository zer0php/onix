<?php

declare(strict_types=1);

namespace Onix\Router;

use Onix\Container\ContainerInterface;
use Onix\Http\Middleware\MiddlewareStack;
use Onix\Http\Middleware\MiddlewareStackHandler;
use Onix\Http\RequestHandlerInterface;
use Onix\Http\ResponseInterface;
use Onix\Http\ServerRequest;
use Onix\Router\Route\MatchedRoute;

class RouteHandler implements RequestHandlerInterface
{
    private ContainerInterface $container;
    private RouteFinder $routeFinder;

    public function __construct(ContainerInterface $container, RouteFinder $routeFinder)
    {
        $this->container = $container;
        $this->routeFinder = $routeFinder;
    }

    /**
     * @param ServerRequest $request
     * @return ResponseInterface
     * @throws RouteNotFoundException
     */
    public function handle(ServerRequest $request): ResponseInterface
    {
        $matchedRoute = $this->getMatchedRouteFromRequest($request);
        $action = $matchedRoute->getRoute()->getAction();
        $attributes = $this->getMergedAttributes($matchedRoute);
        $requestWithAttributes = $request->withAttributes($attributes);

        $middlewareStack = new MiddlewareStack($this->container);
        if (!empty($attributes['_middlewares'])) {
            $this->addMiddlewaresToStack($middlewareStack, (array)$attributes['_middlewares']);
        }
        $middlewareStack->add($action);

        $middlewareStackHandler = new MiddlewareStackHandler($middlewareStack);

        return $middlewareStackHandler->handle($requestWithAttributes);
    }

    private function getMergedAttributes(MatchedRoute $matchedRoute): array
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

    private function addMiddlewaresToStack(MiddlewareStack $middlewareStack, array $middlewareClasses)
    {
        foreach ($middlewareClasses as $middlewareClass) {
            $middlewareStack->add($middlewareClass);
        }
    }
}
