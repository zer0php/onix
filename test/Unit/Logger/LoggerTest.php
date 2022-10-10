<?php

declare(strict_types=1);

namespace OnixTest\Unit\Error;

use Onix\Error\ErrorHandlerMiddleware;
use Onix\Http\RequestHandlerInterface;
use Onix\Http\ResponseInterface;
use Onix\Http\Response\JsonResponse;
use Onix\Http\ServerRequest;
use Exception;
use Onix\Logger\Formatter\JsonFormatter;
use Onix\Logger\Logger;
use Onix\Logger\LoggerInterface;
use PHPUnit\Framework\TestCase;

class LoggerTest extends TestCase
{
    /**
     * @var resource
     */
    private $stream;
    private Logger $logger;

    protected function setUp(): void
    {
        parent::setUp();
        $this->stream = fopen('php://temp', 'w+');
        $this->logger = new Logger($this->stream);
    }

    /**
     * @test
     */
    public function log_WithContext_Perfect(): void
    {
        $this->logger->log(LoggerInterface::LEVEL_INFO, 'example log', ['text' => 'value', 'array' => [1, 2]]);

        $this->assertEquals("INFO: example log [text=value array[0]=1 array[1]=2]\n", $this->getLoggedMessage());
    }

    /**
     * @test
     */
    public function log_WithoutContext_Perfect(): void
    {
        $this->logger->log(LoggerInterface::LEVEL_INFO, 'example log');

        $this->assertEquals("INFO: example log\n", $this->getLoggedMessage());
    }

    /**
     * @test
     */
    public function log_WithJsonFormatterAndContext_Perfect(): void
    {
        $logger = new Logger($this->stream, new JsonFormatter());
        $logger->log(LoggerInterface::LEVEL_INFO, 'example log', ['param' => 'value']);

        $this->assertEquals(
            '{"level":"info","message":"example log","param":"value"}' . "\n",
            $this->getLoggedMessage()
        );
    }

    /**
     * @test
     * @dataProvider levelMethodProvider
     */
    public function testAllLevelMethods_Perfect_Perfect(string $method, $expectedLevel): void
    {
        $this->logger->$method('example log', ['text' => 'value']);

        $this->assertEquals("$expectedLevel: example log [text=value]\n", $this->getLoggedMessage());
    }

    public function levelMethodProvider(): array
    {
        return [
            [LoggerInterface::LEVEL_EMERGENCY, 'EMERGENCY'],
            [LoggerInterface::LEVEL_ALERT, 'ALERT'],
            [LoggerInterface::LEVEL_CRITICAL, 'CRITICAL'],
            [LoggerInterface::LEVEL_ERROR, 'ERROR'],
            [LoggerInterface::LEVEL_WARNING, 'WARNING'],
            [LoggerInterface::LEVEL_NOTICE, 'NOTICE'],
            [LoggerInterface::LEVEL_INFO, 'INFO'],
            [LoggerInterface::LEVEL_DEBUG, 'DEBUG'],
        ];
    }

    private function getLoggedMessage(): string
    {
        rewind($this->stream);

        return fgets($this->stream);
    }
}
