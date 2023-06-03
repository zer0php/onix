<?php

declare(strict_types=1);

namespace Onix\Http;

interface StreamInterface
{
    public function getContents(): string;

    public function getMetadata(): array;

    public function read(int $length): string;

    public function eof(): bool;

    public function write(string $data): int;

    public function seek(int $offset): int;

    public function isSeekable(): bool;

    public function rewind(): bool;
}
