<?php

declare(strict_types=1);

namespace Onix\Http\Client;

use ErrorException;
use Onix\Http\Request;
use Onix\Http\Response;
use Onix\Http\Response\ResponseStack;
use Onix\Http\Stream\ResourceStream;
use Onix\Http\Stream\StringStream;
use InvalidArgumentException;

class NativeAdapter implements AdapterInterface
{
    use HeadersNormalizerTrait;
    
    private array $options;
    
    public function __construct(array $options = [])
    {
        $this->options = $options;
    }

    /**
     * @param Request $request
     * @return resource
     * @throws ErrorException
     */
    public function createConnection(Request $request)
    {
        set_error_handler(static function ($code, $msg, $file, $line) {
            throw new ErrorException($msg, $code, 1, $file, $line);
        });

        $context = $this->getContext(
            $request->getMethod(),
            $request->getBody()->getContents(),
            $request->getHeaders()
        );
        $connection = fopen($request->getUri(), 'rb', false, $context);

        restore_error_handler();

        return $connection;
    }

    /**
     * @param resource $connection
     * @return ResponseStack
     */
    public function getResponseStack($connection): ResponseStack
    {
        $metadata = stream_get_meta_data($connection);

        $responseStack = new ResponseStack();

        if (!isset($metadata['wrapper_data'])) {
            throw new InvalidArgumentException('Invalid HTTP Response');
        }

        foreach ($this->normalizeHeaders($metadata['wrapper_data']) as $headers) {
            $statusCode = $headers['status'];
            unset($headers['status']);

            $responseStack->add(
                new Response(
                    $statusCode,
                    $headers,
                    new ResourceStream($connection)
                )
            );
        }

        return $responseStack;
    }

    /**
     * @param string $method
     * @param string $content [optional]
     * @param array $headers [optional]
     * @param array $options [optional]
     * @return resource
     */
    private function getContext(string $method, string $content = '', array $headers = [], array $options = [])
    {
        $header = '';
        if (count($headers) > 0) {
            $transformedHeaders = $this->getTransformedHeaders($headers);
            $header = implode("\r\n", $transformedHeaders);
            $header .= "\r\n";
        }

        $options['http']['method'] = $method;
        $options['http']['header'] = $header;
        $options['http']['content'] = $content;
        $options['http']['ignore_errors'] = true;

        if (isset($this->options['http'])) {
            $options['http'] = array_merge($options['http'], $this->options['http']);
        }

        return stream_context_create($options);
    }

    private function getTransformedHeaders(array $headers): array
    {
        $transformedHeaders = [];

        foreach ($headers as $key => $value) {
            $transformedHeaders[] = $key . ': ' . $value;
        }

        return $transformedHeaders;
    }
}
