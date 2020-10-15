<?php

declare(strict_types=1);

namespace Onix\Router\Route;

class CompiledRoute
{
    private Route $route;
    private string $pattern;

    public function __construct(Route $route, string $pattern)
    {
        $this->route = $route;
        $this->pattern = $pattern;
    }

    public function getRoute(): Route
    {
        return $this->route;
    }

    public function match(string $requestPath): ?array
    {
        $regexPattern = '/^' . addcslashes($this->pattern, '/') . '$/Us';
        if (!preg_match($regexPattern, $requestPath, $routeMatches)) {
            return null;
        }

        return $this->normalizeMatches($routeMatches);
    }

    private function normalizeMatches(array $matches): array
    {
        $parametersWithoutFirstElement = array_slice($matches, 1);

        return array_filter($parametersWithoutFirstElement, static function ($key) {
            return !is_int($key);
        }, ARRAY_FILTER_USE_KEY);
    }
}
