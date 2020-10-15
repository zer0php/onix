<?php

declare(strict_types=1);

namespace Onix\Http\Response;

use Onix\Http\ResponseInterface;

class Emitter
{
    public function emit(ResponseInterface $response): void
    {
        $statusCode = $response->getStatusCode();
        $headers = $response->getHeaders();

        header("HTTP/1.1 ${statusCode} OK");

        foreach ($headers as $headerKey => $headerValue) {
            header($headerKey . ':' . $headerValue);
        }

        echo $response->getBody()->getContents();
    }
}
