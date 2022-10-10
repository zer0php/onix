<?php

declare(strict_types=1);

namespace Onix\Logger\Decorator;

interface DecoratorInterface
{
    public function decorate(array $record): array;
}
