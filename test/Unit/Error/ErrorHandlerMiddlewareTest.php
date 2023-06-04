<?php

declare(strict_types=1);

namespace OnixTest\Unit\Error;

use Onix\Error\ErrorHandlerMiddleware;
use Onix\Http\RequestHandlerInterface;
use Onix\Http\ResponseInterface;
use Onix\Http\Response\JsonResponse;
use Onix\Http\ServerRequest;
use Onix\Logger\LoggerInterface;
use Exception;
use PHPUnit\Framework\TestCase;

class ErrorHandlerMiddlewareTest extends TestCase
{
    private LoggerInterface $loggerMock;
    private ErrorHandlerMiddleware $errorHandlerMiddleware;

    protected function setUp(): void
    {
        $this->loggerMock = $this->createMock(LoggerInterface::class);
        $this->errorHandlerMiddleware = new ErrorHandlerMiddleware($this->loggerMock);
    }

    /**
     * @test
     */
    public function process_WithoutError_ReturnsHandlerResponse(): void
    {
        $request = $this->createMock(ServerRequest::class);
        $response = $this->createMock(ResponseInterface::class);
        $handler = $this->createMock(RequestHandlerInterface::class);
        $handler
            ->expects($this->once())
            ->method('handle')
            ->willReturn($response);

        $result = $this->errorHandlerMiddleware->process($request, $handler);

        $this->assertEquals($response, $result);
    }

    /**
     * @test
     */
    public function process_DebugOnWithException_ReturnsJsonResponseWithErrorMessage(): void
    {
        $errorMessage = 'Something went wrong';
        $debugOn = true;
        $errorHandler = new ErrorHandlerMiddleware($this->loggerMock, $debugOn);

        $request = $this->createMock(ServerRequest::class);
        $handler = $this->createMock(RequestHandlerInterface::class);
        $handler
            ->expects($this->once())
            ->method('handle')
            ->willThrowException(new Exception($errorMessage));

        $response = $errorHandler->process($request, $handler);

        $this->assertErrorResponse($response, $errorMessage);
    }

    /**
     * @test
     */
    public function process_DebugOnWithPhpError_ReturnsJsonResponseWithErrorMessage(): void
    {
        $errorMessage = 'Something went wrong';
        $debugOn = true;
        $errorHandler = new ErrorHandlerMiddleware($this->loggerMock, $debugOn);

        $handler = $this->createMock(RequestHandlerInterface::class);
        $request = $this->createMock(ServerRequest::class);
        $handler
            ->expects($this->once())
            ->method('handle')
            ->willReturnCallback(function () use ($errorMessage) {
                trigger_error($errorMessage);

                return $this->createMock(ResponseInterface::class);
            });

        $response = $errorHandler->process($request, $handler);

        $this->assertErrorResponse($response, $errorMessage);
    }

    /**
     * @test
     */
    public function process_DebugOnWithError_ReturnsJsonResponseWithErrorMessage(): void
    {
        $errorMessage = 'Something went wrong';
        $debugOn = true;
        $errorHandler = new ErrorHandlerMiddleware($this->loggerMock, $debugOn);

        $handler = $this->createMock(RequestHandlerInterface::class);
        $request = $this->createMock(ServerRequest::class);
        $handler
            ->expects($this->once())
            ->method('handle')
            ->willThrowException(new \Error($errorMessage));

        $response = $errorHandler->process($request, $handler);

        $this->assertErrorResponse($response, $errorMessage);
    }
    
    /**
     * @test
     */
    public function process_DebugOffWithError_ReturnsJsonResponseWithErrorMessage(): void
    {
        $expectedErrorMessage = 'Internal Server Error';
        $debugOn = false;
        $errorHandler = new ErrorHandlerMiddleware($this->loggerMock, $debugOn);

        $handler = $this->createMock(RequestHandlerInterface::class);
        $request = $this->createMock(ServerRequest::class);
        $handler
            ->expects($this->once())
            ->method('handle')
            ->willThrowException(new \Error('Something went wrong'));

        $response = $errorHandler->process($request, $handler);

        $this->assertErrorResponse($response, $expectedErrorMessage);
    }

    /**
     * @test
     */
    public function process_ErrorHandlerRestored(): void
    {
        $request = $this->createMock(ServerRequest::class);
        $handler = $this->createMock(RequestHandlerInterface::class);
        $handler
            ->expects($this->once())
            ->method('handle')
            ->willReturn($this->createMock(ResponseInterface::class));

        $this->errorHandlerMiddleware->process($request, $handler);

        $this->expectError();
        trigger_error('error');
    }

    private function assertErrorResponse(ResponseInterface $response, string $message): void
    {
        $this->assertInstanceOf(JsonResponse::class, $response);

        $data = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertEquals($message, $data['error']);
    }
}
