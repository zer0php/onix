<?php

declare(strict_types=1);

namespace Onix\Logger;

use DateTimeInterface;
use Onix\Logger\Decorator\DecoratorInterface;
use Onix\Logger\Decorator\TimestampDecorator;
use Onix\Logger\Formatter\FormatterInterface;
use Onix\Logger\Formatter\TextFormatter;

class Logger implements LoggerInterface
{
    /**
     * @var resource
     */
    private $stream;
    private FormatterInterface $formatter;
    /**
     * @var array<DecoratorInterface>
     */
    private array $decorators;

    public static function createStdoutLogger(
        ?FormatterInterface $formatter = null,
        DecoratorInterface...$decorators
    ): self {
        $decorators[] = new TimestampDecorator();

        return new Logger(fopen('php://stdout', 'w+'), $formatter, ...$decorators);
    }

    public function __construct($stream, ?FormatterInterface $formatter = null, DecoratorInterface...$decorators)
    {
        $this->stream = $stream;
        $this->formatter = $formatter ?? new TextFormatter();
        $this->decorators = $decorators;
    }

    public function log(string $level, string $message, array $context = []): void
    {
        $record = array_merge(['level' => $level, 'message' => $message], $context);
        foreach ($this->decorators as $decorator) {
            $record = $decorator->decorate($record);
        }

        fwrite($this->stream, $this->formatter->format($record) . PHP_EOL);
    }

    public function emergency(string $message, array $context = []): void
    {
        $this->log(self::LEVEL_EMERGENCY, $message, $context);
    }

    public function alert(string $message, array $context = []): void
    {
        $this->log(self::LEVEL_ALERT, $message, $context);
    }

    public function critical(string $message, array $context = []): void
    {
        $this->log(self::LEVEL_CRITICAL, $message, $context);
    }

    public function error(string $message, array $context = []): void
    {
        $this->log(self::LEVEL_ERROR, $message, $context);
    }

    public function warning(string $message, array $context = []): void
    {
        $this->log(self::LEVEL_WARNING, $message, $context);
    }

    public function notice(string $message, array $context = []): void
    {
        $this->log(self::LEVEL_NOTICE, $message, $context);
    }

    public function info(string $message, array $context = []): void
    {
        $this->log(self::LEVEL_INFO, $message, $context);
    }

    public function debug(string $message, array $context = []): void
    {
        $this->log(self::LEVEL_DEBUG, $message, $context);
    }
}
