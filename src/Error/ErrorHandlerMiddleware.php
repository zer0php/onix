<?php

declare(strict_types=1);

namespace Onix\Error;

use Onix\Http\MiddlewareInterface;
use Onix\Http\RequestHandlerInterface;
use Onix\Http\ResponseInterface;
use Onix\Http\Response\JsonResponse;
use Onix\Http\ServerRequest;
use ErrorException;
use Throwable;

class ErrorHandlerMiddleware implements MiddlewareInterface
{
    public function process(ServerRequest $request, RequestHandlerInterface $handler): ResponseInterface
    {
        set_error_handler(static function ($severity, $message, $file, $line) {
            throw new ErrorException($message, 0, $severity, $file, $line);
        }, E_ALL);

        try {
            $response = $handler->handle($request);
        } catch (Throwable $throwable) {
            $response = new JsonResponse([
                'error' => $throwable->getMessage(),
                'trace' => $throwable->getTraceAsString()
            ], 500);
        }

        restore_error_handler();

        return $response;
    }
}
