<?php

declare(strict_types=1);

namespace Onix\Http\Response;

use Onix\Http\ResponseInterface;
use Onix\Http\StreamInterface;

abstract class AbstractResponse implements ResponseInterface
{
    private StreamInterface $body;
    private int $statusCode;
    private array $headers;

    public function __construct(StreamInterface $body, int $statusCode = 200, array $headers = [])
    {
        $this->body = $body;
        $this->statusCode = $statusCode;
        $this->headers = $headers;
    }

    public function getBody(): StreamInterface
    {
        return $this->body;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }
}
