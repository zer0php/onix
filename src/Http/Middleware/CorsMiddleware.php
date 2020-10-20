<?php

namespace Onix\Http\Middleware;

use Onix\Http\MiddlewareInterface;
use Onix\Http\RequestHandlerInterface;
use Onix\Http\Response;
use Onix\Http\ResponseInterface;
use Onix\Http\ServerRequest;

class CorsMiddleware implements MiddlewareInterface
{
    private string $allowOrigin;
    private string $allowMethods;

    public function __construct(string $allowOrigin = '*', $allowMethods = 'GET, POST, OPTIONS')
    {
        $this->allowOrigin = $allowOrigin;
        $this->allowMethods = $allowMethods;
    }

    public function process(ServerRequest $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($request->getMethod() !== 'OPTIONS') {
            $response = $handler->handle($request);

            return $response->withHeader('Access-Control-Allow-Origin', $this->allowOrigin);
        }

        $responseHeaders = [];
        $requestHeaders = $request->getHeaders();

        if (isset($requestHeaders['Access-Control-Request-Method'])) {
            $responseHeaders['Access-Control-Allow-Methods'] = $this->allowMethods;
        }

        if (isset($requestHeaders['Access-Control-Request-Headers'])) {
            $responseHeaders['Access-Control-Allow-Headers'] = $requestHeaders['Access-Control-Request-Headers'];
        }

        return new Response(200, $responseHeaders);
    }
}
