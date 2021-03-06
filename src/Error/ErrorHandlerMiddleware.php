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
    private bool $debug;

    public function __construct(bool $debug = false)
    {
        $this->debug = $debug;
    }

    public function process(ServerRequest $request, RequestHandlerInterface $handler): ResponseInterface
    {
        set_error_handler(static function ($severity, $message, $file, $line) {
            throw new ErrorException($message, 0, $severity, $file, $line);
        }, E_ALL);

        try {
            $response = $handler->handle($request);
        } catch (Throwable $throwable) {
            if ($this->debug) {
                $response = new JsonResponse([
                    'error' => $throwable->getMessage(),
                    'trace' => $throwable->getTraceAsString()
                ], 500);
            } else {
                $response = new JsonResponse([
                    'error' => 'Internal Server Error',
                ], 500);
            }
        }

        restore_error_handler();

        return $response;
    }
}
