<?php

declare(strict_types=1);

namespace Onix\Console;

use InvalidArgumentException;
use IteratorAggregate;
use JsonSerializable;
use Traversable;

class ArgumentCollection implements IteratorAggregate, JsonSerializable
{
    private const FIRST_INDEX = 1;

    private array $parsedArguments;
    /** @var Argument[] */
    private array $compiledArguments;

    public function __construct(array $parsedArguments)
    {
        $this->parsedArguments = $parsedArguments;
    }

    public function hasArgument(int $index): bool
    {
        return isset($this->parsedArguments[$index]);
    }

    public function getFirstArgument(): Argument
    {
        return $this->getArgument(self::FIRST_INDEX);
    }

    public function getArgument(int $index): Argument
    {
        if (!$this->hasArgument($index)) {
            throw new InvalidArgumentException('Argument not found for given index: ' . $index);
        }

        if (!isset($this->compiledArguments[$index])) {
            $options = $this->parsedArguments[$index];
            $name = array_shift($options);

            $this->compiledArguments[$index] = $this->compileArgument($name, $options);
        }

        return $this->compiledArguments[$index];
    }

    public function getIterator(): Traversable
    {
        foreach (array_keys($this->parsedArguments) as $index) {
            yield $this->getArgument($index);
        }
    }

    public function jsonSerialize(): array
    {
        return $this->parsedArguments;
    }

    private function compileArgument(string $name, array $options): Argument
    {
        return new Argument($name, $options);
    }
}
