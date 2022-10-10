<?php

declare(strict_types=1);

namespace Onix\Logger\Formatter;

interface FormatterInterface
{
    public function format(string $level, string $message, array $context = []): string;
}
