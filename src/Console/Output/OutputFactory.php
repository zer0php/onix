<?php

declare(strict_types=1);

namespace Onix\Console\Output;

class OutputFactory
{
    public function create($stream): OutputInterface
    {
        return new Output(
            is_resource($stream) ? $stream : fopen($stream, 'w+'),
        );
    }
}
