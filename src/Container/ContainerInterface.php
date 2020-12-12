<?php

declare(strict_types=1);

namespace Onix\Container;

interface ContainerInterface
{
    /**
     * @param string $name
     * @return mixed
     * @throws NotFoundException
     */
    public function get(string $name);

    public function has(string $name): bool;
}
