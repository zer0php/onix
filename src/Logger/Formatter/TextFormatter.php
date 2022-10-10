<?php

declare(strict_types=1);

namespace Onix\Logger\Formatter;

class TextFormatter implements FormatterInterface
{
    public function format(string $level, string $message, array $context = []): string
    {
        $level = strtoupper($level);
        $result = "$level: $message";

        if (count($context) > 0) {
            $formattedContext = urldecode(http_build_query($context, '', ' '));
            $result .= " [$formattedContext]";
        }

        return $result;
    }
}
