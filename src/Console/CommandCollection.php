<?php

namespace Onix\Console;

use InvalidArgumentException;
use Onix\Container\ContainerInterface;
use Onix\Container\NotFoundException;

class CommandCollection
{
    private ContainerInterface $container;
    private array $commands = [];

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function add(string $commandName, string $commandClass)
    {
        $this->commands[$commandName] = $commandClass;
    }

    public function getCommandList(): array
    {
        return array_keys($this->commands);
    }

    /**
     * @param string $commandName
     * @return CommandInterface
     * @throws NotFoundException
     */
    public function get(string $commandName): CommandInterface
    {
        if (!isset($this->commands[$commandName])) {
            throw new InvalidArgumentException('Command not found for given name: ' . $commandName);
        }

        return $this->container->get($this->commands[$commandName]);
    }
}
