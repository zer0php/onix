<?php

declare(strict_types=1);

namespace Onix\Http\Middleware;

use Onix\Container\Container;
use Onix\Http\MiddlewareInterface;
use Onix\Http\RequestHandlerInterface;
use RuntimeException;

class MiddlewareStack
{
    private Container $container;
    private array $middlewareStack = [];

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function add(string $middleware): void
    {
        $this->middlewareStack[] = $middleware;
    }

    public function next(): MiddlewareInterface
    {
        $middlewareClass = array_shift($this->middlewareStack);

        if ($middlewareClass === null) {
            throw new RuntimeException('MiddlewareStack is empty!');
        }

        $middleware = $this->container->get($middlewareClass);

        if ($middleware instanceof RequestHandlerInterface) {
            return new RequestHandlerMiddleware($middleware);
        }

        return $middleware;
    }
}
