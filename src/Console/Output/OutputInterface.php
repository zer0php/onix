<?php

declare(strict_types=1);

namespace Onix\Console\Output;

interface OutputInterface
{
    public function read(int $length): string;
    public function readLine(): string;
    public function write(string $data): int;
    public function writeln(string $data): int;
}
