<?php

namespace Onix\Console\Input;

use Onix\Console\ArgumentCollection;

class Input implements InputInterface
{
    /**
     * @var resource
     */
    private $stream;

    private ArgumentCollection $arguments;

    public function __construct($stream, ArgumentCollection $arguments)
    {
        $this->stream = $stream;
        $this->arguments = $arguments;
    }

    public function getArguments(): ArgumentCollection
    {
        return $this->arguments;
    }

    public function withArguments(ArgumentCollection $arguments)
    {
        $new = clone $this;
        $new->arguments = $arguments;
        return $new;
    }

    public function read(int $length): string
    {
        return (string)fread($this->stream, $length);
    }

    public function readLine(): string
    {
        return (string)fgets($this->stream);
    }

    public function write(string $data): int
    {
        return (int)fwrite($this->stream, $data);
    }
}
