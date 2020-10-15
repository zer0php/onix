<?php

declare(strict_types=1);

namespace Onix\Router;

interface RouteCollectorInterface
{
    public function route(string $method, string $template, string $action, array $attributes = []);
    public function get(string $template, string $action, array $attributes = []);
    public function post(string $template, string $action, array $attributes = []);
    public function patch(string $template, string $action, array $attributes = []);
    public function put(string $template, string $action, array $attributes = []);
    public function delete(string $template, string $action, array $attributes = []);
    public function head(string $template, string $action, array $attributes = []);
    public function trace(string $template, string $action, array $attributes = []);
    public function setNotFoundRoute(string $action, array $attributes = []);
}
