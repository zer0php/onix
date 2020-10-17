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

    public function __construct($resource)
    {
        $this->resource = $resource;
    }

    public function getContents(): string
    {
        return stream_get_contents($this->resource);
    }
}
