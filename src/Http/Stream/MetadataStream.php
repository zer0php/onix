<?php

declare(strict_types=1);

namespace Onix\Http\Stream;

use Onix\Http\StreamInterface;

class MetadataStream extends ResourceStream
{
    private array $metadata;

    /**
     * @param string|resource $resource
     * @param array $metadata
     */
    public function __construct($resource, array $metadata)
    {
        parent::__construct($resource);

        $this->metadata = $metadata;
    }

    public function getMetadata(): array
    {
        return $this->metadata;
    }
}
