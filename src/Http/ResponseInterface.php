<?php

declare(strict_types=1);

namespace Onix\Http;

interface ResponseInterface
{
    public function getBody(): StreamInterface;

    public function getStatusCode(): int;

    public function getHeaders(): array;

    public function withHeader(string $key, string $value): self;
}
