<?php

declare(strict_types=1);

namespace Onix\Router\Route;

class Route implements RouteInterface
{
    private string $template;
    private string $action;
    private array $attributes;

    public function __construct(string $template, string $action, array $attributes = [])
    {
        $this->template = $template;
        $this->action = $action;
        $this->attributes = $attributes;
    }

    public function getTemplate(): string
    {
        return $this->template;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }
}
