<?php

namespace Onix\Logger;

class Logger implements LoggerInterface
{
    /**
     * @var resource
     */
    private $stream;

    public function __construct($stream)
    {
        $this->stream = $stream;
    }

    public function log(string $level, string $message, array $context = [])
    {
        fwrite($this->stream, '[' . $level . '] ' . $message . PHP_EOL);
    }

    public function info(string $message, array $context = [])
    {
        $this->log('INFO', $message, $context);
    }

    public function debug(string $message, array $context = [])
    {
        $this->log('DEBUG', $message, $context);
    }

    public function warning(string $message, array $context = [])
    {
        $this->log('WARNING', $message, $context);
    }

    public function error(string $message, array $context = [])
    {
        $this->log('ERROR', $message, $context);
    }
}
