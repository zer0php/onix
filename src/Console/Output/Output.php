<?php

namespace Onix\Console\Output;

class Output implements OutputInterface
{
    /**
     * @var resource
     */
    private $stream;

    public function __construct($stream)
    {
        $this->stream = $stream;
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
    
    public function writeln(string $data): int
    {
        return $this->write($data . PHP_EOL);
    }
}
