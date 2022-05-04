<?php

declare(strict_types=1);

namespace Onix\Http\Client;

use Onix\Http\Request;
use Onix\Http\Response\ResponseStack;

interface AdapterInterface
{
    public function createConnection(Request $request);

    public function getResponseStack($connection): ResponseStack;
}
