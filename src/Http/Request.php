<?php

declare(strict_types=1);

namespace Onix\Http;

use Onix\Http\Stream\StringStream;

class Request
{
    private string $method;
    private string $uri;
    private array $headers;
    private StreamInterface $body;

    public function __construct(string $method, string $uri, array $headers = [], ?StreamInterface $body = null)
    {
        $this->method = $method;
        $this->uri = $uri;
        $this->headers = $headers;
        $this->body = $body ?? new StringStream();
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getRequestUri(): string
    {
        return $this->uri;
    }

    public function getPath(): string
    {
        return parse_url($this->uri, PHP_URL_PATH);
    }

    public function getBody(): StreamInterface
    {
        return $this->body;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function withUri(string $uri): self
    {
        $new = clone $this;
        $new->uri = $uri;

        return $new;
    }
}
