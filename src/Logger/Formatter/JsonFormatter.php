<?php

declare(strict_types=1);

namespace Onix\Logger\Formatter;

class JsonFormatter implements FormatterInterface
{
    public function format(array $record): string
    {
        return json_encode(
            $record,
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
        );
    }
}
