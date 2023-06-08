<?php

declare(strict_types=1);

namespace Onix\Console;

class Argument
{
    private string $value;
    private array $options;

    public function __construct(string $value, array $options = [])
    {
        $this->value = $value;
        $this->options = $options;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getOptions(): array
    {
        return $this->options;
    }
}
