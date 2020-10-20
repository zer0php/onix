<?php

declare(strict_types=1);

namespace Onix\Http;

use Onix\Http\Stream\ResourceStream;

class ServerRequest extends Request
{
    private array $serverParams;
    private array $queryParams;
    private array $parsedBody;
    private array $cookieParams;
    private array $attributes;
    private array $files;

    public static function fromGlobals(): self
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $headers = getallheaders();
        $body = new ResourceStream(fopen('php://input', 'rb'));

        return new self($method, $uri, $headers, $body, $_SERVER, $_GET, $_POST, $_COOKIE, $_FILES);
    }

    public function __construct(
        string $method,
        string $uri,
        array $headers = [],
        ?StreamInterface $body = null,
        array $server = [],
        array $queryParams = [],
        array $parsedBody = [],
        array $cookieParams = [],
        array $files = []
    ) {
        parent::__construct($method, $uri, $headers, $body);

        $this->serverParams = $server;
        $this->queryParams = $queryParams;
        $this->parsedBody = $parsedBody;
        $this->cookieParams = $cookieParams;
        $this->files = $files;
    }

    public function getServerParams(): array
    {
        return $this->serverParams;
    }

    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    public function getParsedBody(): array
    {
        return $this->parsedBody;
    }

    public function getCookieParams(): array
    {
        return $this->cookieParams;
    }

    public function getFiles(): array
    {
        return $this->files;
    }

    public function withAttributes(array $attributes): self
    {
        $new = clone $this;
        $new->attributes = $attributes;

        return $new;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @param string $name
     * @param mixed|null $default
     * @return mixed
     */
    public function getAttribute(string $name, $default = null)
    {
        return $this->attributes[$name] ?? $default;
    }
}
