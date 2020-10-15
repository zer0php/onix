<?php

declare(strict_types=1);

namespace Onix\Http;

interface StreamInterface
{
    public function getContents(): string;
}
