<?php

declare(strict_types=1);

namespace Onix\Http;

interface MiddlewareInterface
{
    public function process(ServerRequest $request, RequestHandlerInterface $handler): ResponseInterface;
}
