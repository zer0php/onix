<?php

declare(strict_types=1);

namespace Onix\Error;

use Onix\Http\MiddlewareInterface;
use Onix\Http\RequestHandlerInterface;
use Onix\Http\ResponseInterface;
use Onix\Http\Response\JsonResponse;
use Onix\Http\ServerRequest;
use Onix\Logger\LoggerInterface;
use ErrorException;
use Throwable;

class ErrorHandlerMiddleware implements MiddlewareInterface
{
    private LoggerInterface $logger;
    private bool $debug;

    public function __construct(LoggerInterface $logger, bool $debug = false)
    {
        $this->logger = $logger;
        $this->debug = $debug;
    }

    public function process(ServerRequest $request, RequestHandlerInterface $handler): ResponseInterface
    {
        set_error_handler(static function ($severity, $message, $file, $line) {
            throw new ErrorException($message, 0, $severity, $file, $line);
        }, E_ALL);

        try {
            $response = $handler->handle($request);
        } catch (Throwable $e) {
            $this->logger->error($e->getMessage(), [
                'error_class' => get_class($e),
                'error_code' => $e->getCode(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'error_trace' => $e->getTraceAsString()
            ]);
            if ($this->debug) {
                $response = new JsonResponse([
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
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
