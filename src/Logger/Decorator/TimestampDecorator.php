<?php

declare(strict_types=1);

namespace Onix\Logger\Decorator;

class TimestampDecorator implements DecoratorInterface
{
    public const DATE_FORMAT = 'Y-m-d\TH:i:s.uP';

    private string $format;

    public function __construct(string $format = self::DATE_FORMAT)
    {
        $this->format = $format;
    }

    public function decorate(array $record): array
    {
        $record['timestamp'] = date($this->format);

        return $record;
    }
}
