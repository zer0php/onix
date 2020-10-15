<?php

declare(strict_types=1);

namespace Onix\Router;

use Onix\Router\Route\CompiledRoute;
use Onix\Router\Route\Route;

class RouteCompiler
{
    private const ROUTE_PATTERN = '/(:[A-Za-z_]+)/';
    private RouteFactory $routeFactory;

    public function __construct(RouteFactory $routeFactory)
    {
        $this->routeFactory = $routeFactory;
    }

    public function compile(Route $route): CompiledRoute
    {
        $pattern = $this->compilePattern($route->getTemplate());

        return $this->routeFactory->createCompiledRoute($route, $pattern);
    }

    private function compilePattern(string $template): string
    {
        $replacer = static function ($match) {
            return '(?<' . substr($match[1], 1) . '>.+)';
        };

        return (string)preg_replace_callback(self::ROUTE_PATTERN, $replacer, $template);
    }
}
