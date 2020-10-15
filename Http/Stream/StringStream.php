<?php

declare(strict_types=1);

namespace Onix\Http\Stream;

use Onix\Http\StreamInterface;

class StringStream implements StreamInterface
{
    private string $data;

    public function __construct(string $data = '')
    {
        $this->data = $data;
    }

    public function getContents(): string
    {
        return $this->data;
    }
}
