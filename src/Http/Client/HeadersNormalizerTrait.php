<?php

declare(strict_types=1);

namespace Onix\Http\Client;

trait HeadersNormalizerTrait
{
    private function normalizeHeaders(array $headers): array
    {
        $normalizedHeaders = [];

        $index = 0;
        foreach ($headers as $header) {
            if (strpos($header, 'HTTP') === 0) {
                if (count($normalizedHeaders) > 0) {
                    $index++;
                }

                [, $status] = explode(' ', $header);
                $normalizedHeaders[$index]['status'] = (int)$status;

                continue;
            }

            [$key, $value] = explode(':', $header, 2);
            if (!isset($normalizedHeaders[$index][$key])) {
                $normalizedHeaders[$index][$key] = [];
            }

            $normalizedHeaders[$index][$key][] = trim($value);
        }

        return $normalizedHeaders;
    }
}
