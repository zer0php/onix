<?php

declare(strict_types=1);

namespace Onix\Http\Stream;

use Onix\Http\StreamInterface;

class JsonStream extends StringStream
{
    public function __construct(array $data)
    {
        parent::__construct($this->getJsonFromData($data));
    }

    private function getJsonFromData(array $data): string
    {
        return json_encode(
            $data,
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR,
            512
        );
    }
}
