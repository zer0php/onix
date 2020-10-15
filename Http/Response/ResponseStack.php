<?php

declare(strict_types=1);

namespace Onix\Http\Response;

use Onix\Http\ResponseInterface;
use Onix\Http\StreamInterface;
use ArrayIterator;
use IteratorAggregate;
use Traversable;

class ResponseStack implements ResponseInterface, IteratorAggregate
{
    private array $stack = [];

    public function add(ResponseInterface $response)
    {
        $this->stack[] = $response;
    }

    public function getBody(): StreamInterface
    {
        return end($this->stack)->getBody();
    }

    public function getStatusCode(): int
    {
        return end($this->stack)->getStatusCode();
    }

    public function getHeaders(): array
    {
        return end($this->stack)->getHeaders();
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->stack);
    }
}
