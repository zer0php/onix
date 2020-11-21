<?php

declare(strict_types=1);

namespace OnixTest\Unit\Error;

use Onix\Error\ErrorHandlerMiddleware;
use Onix\Http\RequestHandlerInterface;
use Onix\Http\ResponseInterface;
use Onix\Http\Response\JsonResponse;
use Onix\Http\ServerRequest;
use Exception;
use PHPUnit\Framework\TestCase;

class ErrorHandlerMiddlewareTest extends TestCase
{
    private ErrorHandlerMiddleware $errorHandlerMiddleware;

    protected function setUp(): void
    {
        $this->errorHandlerMiddleware = new ErrorHandlerMiddleware();
    }

    /**
     * @test
     */
    public function process_WithoutError_ReturnsHandlerResponse(): void
    {
        $errorHandler = new ErrorHandlerMiddleware();

        $request = $this->createMock(ServerRequest::class);
        $response = $this->createMock(ResponseInterface::class);
        $handler = $this->createMock(RequestHandlerInterface::class);
        $handler
            ->expects($this->once())
            ->method('handle')
            ->willReturn($response);

        $result = $errorHandler->process($request, $handler);

        $this->assertEquals($response, $result);
    }

    /**
     * @test
     */
    public function process_DebugOnWithException_ReturnsJsonResponseWithErrorMessage(): void
    {
        $errorMessage = 'Something went wrong';
        $debugOn = true;
        $errorHandler = new ErrorHandlerMiddleware($debugOn);

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
        $errorHandler = new ErrorHandlerMiddleware($debugOn);

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
        $errorHandler = new ErrorHandlerMiddleware($debugOn);

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
        $errorMessage = 'Something went wrong';
        $expectedErrorMessage = 'Internal Server Error';
        $debugOn = false;
        $errorHandler = new ErrorHandlerMiddleware($debugOn);

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
        $this->assertArrayHasKey('trace', $data);
    }
}
