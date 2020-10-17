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
     * @param Container $container
     * @param string $name
     * @return mixed
     * @throws NotFoundException
     * @throws ContainerException
     * @throws ReflectionException
     */
    public function __invoke(Container $container, string $name)
    {
        return $this->resolve($this->getReflectionClass($name), $container);
    }

    /**
     * @param ReflectionClass $reflectionClass
     * @param Container $container
     * @return object
     * @throws ContainerException
     * @throws NotFoundException
     * @throws ReflectionException
     */
    private function resolve(ReflectionClass $reflectionClass, Container $container)
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
     * @param string $name
     * @return ReflectionClass
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
