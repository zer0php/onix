<?php

declare(strict_types=1);

namespace Onix\Http\Stream;

use Onix\Http\StreamInterface;

class JsonStream implements StreamInterface
{
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getContents(): string
    {
        return json_encode($this->data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR, 512);
    }
}
