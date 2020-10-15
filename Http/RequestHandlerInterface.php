<?php

declare(strict_types=1);

namespace Onix\Http;

interface RequestHandlerInterface
{
    public function handle(ServerRequest $request): ResponseInterface;
}
