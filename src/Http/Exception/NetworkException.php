<?php

declare(strict_types=1);

namespace Onix\Http\Exception;

use Exception;
use Onix\Http\Response;
use Onix\Http\ResponseInterface;

class NetworkException extends Exception
{
    private ResponseInterface $response;

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
