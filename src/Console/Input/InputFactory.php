<?php

namespace Onix\Console\Input;

use Onix\Console\ArgumentCollection;

class InputFactory
{
    public function create($stream, ArgumentCollection $arguments): InputInterface
    {
        return new Input(
            is_resource($stream) ? $stream : fopen($stream, 'r'),
            $arguments
        );
    }
}
