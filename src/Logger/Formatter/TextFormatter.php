<?php

declare(strict_types=1);

namespace Onix\Logger\Formatter;

class TextFormatter implements FormatterInterface
{
    public function format(array $record): string
    {
        $level = strtoupper($record['level']);
        $message = $record['message'];
        unset($record['level'], $record['message']);

        $formattedContext = count($record) > 0
            ? sprintf(' [%s]', urldecode(http_build_query($record, '', ' ')))
            : '';

        return sprintf('%s: %s%s', $level, $message, $formattedContext);
    }
}
