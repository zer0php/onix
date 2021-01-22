<?php

declare(strict_types=1);

namespace Onix\Http;

interface ClientInterface
{
    public function sendRequest(Request $request): ResponseInterface;
}
