<?php

declare(strict_types=1);

namespace Onix\Container;

class Container implements ContainerInterface
{
    /**
     * @var array<callable>
     */
    private array $factories;
    /**
     * @var array<object>
     */
    private array $instances = [];
    /**
     * @var callable|null
     */
    private $defaultFactory;

    public static function createWithAutoWireFactory(array $factories): self
    {
        return new self($factories, new AutoWireFactory(new ReflectionResolver()));
    }

    /**
     * @param array<callable> $factories
     * @param callable|null $defaultFactory
     */
    public function __construct(array $factories, ?callable $defaultFactory = null)
    {
        $this->factories = $factories;
        $this->defaultFactory = $defaultFactory;
    }

    /**
     * @param string $name
     * @return mixed
     * @throws NotFoundException
     */
    public function get(string $name)
    {
        $factory = $this->getFactory($name);

        if (!$this->has($name)) {
            $this->instances[$name] = $factory($this, $name);
        }

        return $this->instances[$name];
    }

    public function has(string $name): bool
    {
        return isset($this->instances[$name]);
    }

    /**
     * @param string $name
     * @return callable
     * @throws NotFoundException
     */
    private function getFactory(string $name): callable
    {
        if (isset($this->factories[$name])) {
            return $this->factories[$name];
        }

        if ($this->defaultFactory) {
            return $this->defaultFactory;
        }

        throw new NotFoundException('Factory not found for given name: ' . $name);
    }
}
