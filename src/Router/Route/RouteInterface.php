<?php

declare(strict_types=1);

namespace Onix\Router\Route;

interface RouteInterface
{
    public function getTemplate(): string;
    public function getAction(): string;
    public function getAttributes(): array;
}
