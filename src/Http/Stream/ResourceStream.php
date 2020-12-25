<?php

declare(strict_types=1);

namespace Onix\Http\Stream;

use Onix\Http\StreamInterface;

class ResourceStream implements StreamInterface
{
    /**
     * @var resource
     */
    private $resource;

    /**
     * @param string|resource $resource
     */
    public function __construct($resource)
    {
        if (is_string($resource)) {
            $resource = fopen($resource, 'wb+');
        }

        $this->resource = $resource;
    }

    public function getContents(): string
    {
        return stream_get_contents($this->resource);
    }

    public function getMetadata(): array
    {
        return stream_get_meta_data($this->resource);
    }

    public function read(int $length): string
    {
        return (string)fread($this->resource, $length);
    }

    public function write(string $data): int
    {
        return (int)fwrite($this->resource, $data);
    }

    public function seek(int $offset): int
    {
        return fseek($this->resource, $offset);
    }

    public function isSeekable(): bool
    {
        $metadata = $this->getMetadata();

        return isset($metadata['seekable']) && $metadata['seekable'];
    }

    public function rewind(): bool
    {
        return rewind($this->resource);
    }
}
