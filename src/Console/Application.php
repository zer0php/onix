<?php

namespace Onix\Console;

use Onix\Console\Input\InputFactory;
use Onix\Logger\LoggerInterface;
use InvalidArgumentException;
use Onix\Container\NotFoundException;
use Onix\Console\Output\OutputFactory;

class Application
{
    private CommandCollection $commands;
    private ArgumentsParser $argumentParser;
    private InputFactory $inputFactory;
    private OutputFactory $outputFactory;
    private LoggerInterface $logger;

    public function __construct(
        CommandCollection $commands,
        ArgumentsParser $argumentParser,
        InputFactory $inputFactory,
        OutputFactory $outputFactory,
        LoggerInterface $logger
    ) {
        $this->commands = $commands;
        $this->argumentParser = $argumentParser;
        $this->inputFactory = $inputFactory;
        $this->outputFactory = $outputFactory;
        $this->logger = $logger;
    }

    /**
     * @param array $argv
     * @param string|resource $inputStream
     * @param string|resource $outputStream
     * @return int
     */
    public function run(array $argv, $inputStream = 'php://stdin', $outputStream = 'php://stdout'): int
    {
        try {
            $arguments = $this->argumentParser->parse($argv);
            $command = $this->getCommand($arguments);
            $input = $this->inputFactory->create($inputStream, $arguments);
            $output = $this->outputFactory->create($outputStream);

            return $command->run($input, $output);
        } catch (InvalidArgumentException | NotFoundException $exception) {
            $this->logger->error($exception->getMessage());

            return 1;
        }
    }

    /**
     * @param ArgumentCollection $arguments
     * @return CommandInterface
     * @throws InvalidArgumentException | NotFoundException
     */
    private function getCommand(ArgumentCollection $arguments): CommandInterface
    {
        $commandArgument = $arguments->getFirstArgument();

        return $this->commands->get($commandArgument->getValue());
    }
}
