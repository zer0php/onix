<?php

declare(strict_types=1);

namespace Onix\Http\Middleware;

use Onix\Http\MiddlewareInterface;
use Onix\Http\RequestHandlerInterface;
use Onix\Http\ServerRequest;
use Onix\Http\ResponseInterface;

class RequestHandlerMiddleware implements MiddlewareInterface
{
    private RequestHandlerInterface $handler;

    public function __construct(RequestHandlerInterface $handler)
    {
        $this->handler = $handler;
    }

    public function process(ServerRequest $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return $this->handler->handle($request);
    }
}
