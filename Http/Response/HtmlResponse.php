<?php

declare(strict_types=1);

namespace Onix\Http\Response;

use Onix\Http\Response;
use Onix\Http\StreamInterface;

class HtmlResponse extends Response
{
    public function __construct(StreamInterface $body, int $statusCode = 200, array $headers = [])
    {
        $headers['Content-Type'] = 'text/html; charset=utf-8';

        parent::__construct($statusCode, $headers, $body);
    }
}
