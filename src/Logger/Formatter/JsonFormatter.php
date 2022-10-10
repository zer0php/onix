<?php

declare(strict_types=1);

namespace Onix\Logger\Formatter;

class JsonFormatter implements FormatterInterface
{
    public function format(string $level, string $message, array $context = []): string
    {
        return json_encode(
            array_merge(['level' => $level, 'message' => $message], $context),
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
        );
    }
}
