<?php

declare(strict_types=1);

namespace Onix\Http\Middleware;

use Onix\Http\RequestHandlerInterface;
use Onix\Http\ResponseInterface;
use Onix\Http\ServerRequest;

class MiddlewareStackHandler implements RequestHandlerInterface
{
    private MiddlewareStack $middlewareStack;

    public function __construct(MiddlewareStack $middlewareStack)
    {
        $this->middlewareStack = $middlewareStack;
    }

    public function handle(ServerRequest $request): ResponseInterface
    {
        return $this->middlewareStack->next()->process($request, $this);
    }
}
