<?php

declare(strict_types=1);

namespace Onix\Http\Stream;

use Onix\Http\StreamInterface;

class JsonStream extends StringStream implements StreamInterface
{
    public function __construct(array $data)
    {
        parent::__construct($this->getResourceFromData($data));
    }

    /**
     * @param string $data
     * @return false|resource
     */
    private function getResourceFromData(array $data)
    {
        $resource = fopen('php://memory', 'wb+');
        $json = json_encode($this->data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR, 512);
        fwrite($resource, $json);

        return $resource;
    }
}
