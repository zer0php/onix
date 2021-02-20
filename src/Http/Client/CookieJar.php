<?php

declare(strict_types=1);

namespace Onix\Http\Client;

class CookieJar
{
    private array $cookies;

    public function __construct(array $cookies = [])
    {
        $this->cookies = $cookies;
    }

    public function addCookie(string $key, string $value): void
    {
        $this->cookies[$key] = $value;
    }

    public function getCookies(): array
    {
        return $this->cookies;
    }

    public function getCookieLine(): string
    {
        $parsedCookies = [];
        foreach ($this->cookies as $key => $value) {
            $parsedCookies[] = $key . '=' . $value;
        }

        return implode('; ', $parsedCookies);
    }
}
