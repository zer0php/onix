<?php

declare(strict_types=1);

namespace Onix\Container;

use ReflectionException;
use ReflectionMethod;

class ReflectionResolver
{
    /**
     * @param ReflectionMethod $method
     * @param Container $container
     * @return array
     * @throws NotFoundException
     * @throws ReflectionException
     */
    public function resolveArguments(ReflectionMethod $method, Container $container): array
    {
        $arguments = [];

        foreach ($method->getParameters() as $parameter) {
            if ($parameter->isDefaultValueAvailable()) {
                $arguments[] = $parameter->getDefaultValue();
                continue;
            }

            if ($parameter->hasType() && !$parameter->getType()->isBuiltin()) {
                $arguments[] = $container->get($parameter->getType()->getName());
                continue;
            }

            $arguments[] = null;
        }

        return $arguments;
    }
}
