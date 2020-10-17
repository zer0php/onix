<?php

declare(strict_types=1);

namespace Onix\Router;

use Onix\Http\MiddlewareInterface;
use Onix\Http\RequestHandlerInterface;
use Onix\Http\ResponseInterface;
use Onix\Http\ServerRequest;

class RouteHandlerMiddleware implements MiddlewareInterface
{
    private RouteHandler $routeHandler;

    public function __construct(RouteHandler $routeHandler)
    {
        $this->routeHandler = $routeHandler;
    }

    public function process(ServerRequest $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return $this->routeHandler->handle($request);
    }
}
