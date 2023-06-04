<?php

declare(strict_types=1);

namespace Onix\Http\Exception;

use Exception;
use Onix\Http\Response;
use Onix\Http\ResponseInterface;
use Onix\Http\Stream\StringStream;

class NetworkException extends Exception
{
    private ResponseInterface $response;

    public static function createFromString(string $message, int $code = 0): self
    {
        return new self(new Response(0, [], new StringStream($message)));
    }

    public function __construct(ResponseInterface $response)
    {
        parent::__construct($response->getBody()->getContents(), $response->getStatusCode());

        $this->response = $response;
    }

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }
}
