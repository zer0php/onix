<?php

declare(strict_types=1);

namespace Onix\Http;

use Onix\Http\Stream\StringStream;

class Response implements ResponseInterface
{
    private int $statusCode;
    private array $headers;
    private StreamInterface $body;

    public function __construct(int $statusCode = 200, array $headers = [], ?StreamInterface $body = null)
    {
        $this->statusCode = $statusCode;
        $this->headers = $headers;
        $this->body = $body ?? new StringStream('');
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
