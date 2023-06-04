<?php

declare(strict_types=1);

namespace Onix\Http\Stream;

use Onix\Http\StreamInterface;

class StringStream extends ResourceStream
{
    public function __construct(string $data = '')
    {
        $resource = $this->getResourceFromData($data);

        parent::__construct($resource);
    }

    /**
     * @param string $data
     * @return false|resource
     */
    private function getResourceFromData(string $data)
    {
        $resource = fopen('php://memory', 'wb+');
        fwrite($resource, $data);
        rewind($resource);

        return $resource;
    }
}
