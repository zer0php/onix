<?php

declare(strict_types=1);

namespace Onix\Http\Response;

use Onix\Http\ResponseInterface;
use Onix\Http\StreamInterface;
use Onix\Http\Stream\JsonStream;

class JsonResponse implements ResponseInterface
{
    private array $data;
    private int $status;
    private array $headers;

    public function __construct(array $data, int $status = 200, array $headers = [])
    {
        $headers['Content-Type'] = 'application/json; charset=utf-8';

        $this->data = $data;
        $this->status = $status;
        $this->headers = $headers;
    }

    public function getBody(): StreamInterface
    {
        return new JsonStream($this->data);
    }

    public function getStatusCode(): int
    {
        return $this->status;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function withHeader(string $key, string $value): ResponseInterface
    {
        $new = clone $this;
        $new->headers[$key] = $value;

        return $new;
    }
}
