<?php

declare(strict_types=1);

namespace Onix;

use Onix\Http\RequestHandlerInterface;
use Onix\Http\ResponseInterface;
use Onix\Http\Response\Emitter;
use Onix\Http\ServerRequest;

class Application implements RequestHandlerInterface
{
    private RequestHandlerInterface $handler;
    private Emitter $responseEmitter;

    public function __construct(RequestHandlerInterface $handler, Emitter $responseEmitter)
    {
        $this->handler = $handler;
        $this->responseEmitter = $responseEmitter;
    }

    public function run(ServerRequest $request): void
    {
        $response = $this->handle($request);

        $this->responseEmitter->emit($response);
    }

    public function handle(ServerRequest $request): ResponseInterface
    {
        return $this->handler->handle($request);
    }
}
