<?php

namespace Onix\Http\Middleware;

use Onix\Http\MiddlewareInterface;
use Onix\Http\RequestHandlerInterface;
use Onix\Http\Response;
use Onix\Http\ResponseInterface;
use Onix\Http\ServerRequest;

class CorsMiddleware implements MiddlewareInterface
{
    private string $allowedOrigin;
    private string $allowedMethods;

    public function __construct(string $allowedOrigin = '*', $allowedMethods = 'GET, POST, OPTIONS')
    {
        $this->allowedOrigin = $allowedOrigin;
        $this->allowedMethods = $allowedMethods;
    }

    public function process(ServerRequest $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($request->getMethod() !== 'OPTIONS') {
            $response = $handler->handle($request);

            return $response
                ->withHeader('Access-Control-Allow-Origin', $this->allowedOrigin);
        }

        $responseHeaders = [];
        $requestHeaders = $request->getHeaders();

        $responseHeaders['Access-Control-Allow-Origin'] = $this->allowedOrigin;

        if (isset($requestHeaders['Access-Control-Request-Method'])) {
            $responseHeaders['Access-Control-Allow-Methods'] = $this->allowedMethods;
        }

        if (isset($requestHeaders['Access-Control-Request-Headers'])) {
            $responseHeaders['Access-Control-Allow-Headers'] = $requestHeaders['Access-Control-Request-Headers'];
        }

        return new Response(200, $responseHeaders);
    }
}
