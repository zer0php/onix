<?php

declare(strict_types=1);

namespace Onix\Container;

use ReflectionClass;
use ReflectionException;

class AutoWireFactory
{
    private ReflectionResolver $reflectionResolver;
    private array $reflectionClasses = [];

    public function __construct(ReflectionResolver $reflectionResolver)
    {
        $this->reflectionResolver = $reflectionResolver;
    }

    /**
     * @throws NotFoundException
     * @throws ContainerException
     * @throws ReflectionException
     */
    public function __invoke(ContainerInterface $container, string $name): object
    {
        return $this->resolve($this->getReflectionClass($name), $container);
    }

    /**
     * @throws ContainerException
     * @throws NotFoundException
     * @throws ReflectionException
     */
    private function resolve(ReflectionClass $reflectionClass, ContainerInterface $container): object
    {
        if (!$reflectionClass->isInstantiable()) {
            throw new ContainerException('Cannot instantiate ' . $reflectionClass->getName());
        }

        $constructor = $reflectionClass->getConstructor();

        if ($constructor !== null) {
            $arguments = $this->reflectionResolver->resolveArguments($constructor, $container);

            return $reflectionClass->newInstanceArgs($arguments);
        }

        return $reflectionClass->newInstance();
    }

    /**
     * @throws ReflectionException
     */
    private function getReflectionClass(string $name): ReflectionClass
    {
        if (!isset($this->reflectionClasses[$name])) {
            $this->reflectionClasses[$name] = new ReflectionClass($name);
        }

        return $this->reflectionClasses[$name];
    }
}
