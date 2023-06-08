<?php

declare(strict_types=1);

namespace Onix\Console\Input;

use Onix\Console\ArgumentCollection;

interface InputInterface
{
    public function getArguments(): ArgumentCollection;
    public function read(int $length): string;
    public function readLine(): string;
    public function write(string $data): int;
}
