<?php

declare(strict_types=1);

namespace Onix\Http;

use Onix\Http\Response\ResponseStack;
use Onix\Http\Stream\ResourceStream;
use Onix\Http\Stream\StringStream;

class Client
{

    /**
     * @param string $url
     * @param string|array $query [optional]
     * @param array $headers [optional]
     * @return ResponseInterface
     */
    public function get(string $url, $query = '', array $headers = []): ResponseInterface
    {
        if (is_array($query)) {
            $url .= '?' . http_build_query($query);
        } elseif ($query !== '') {
            $url .= '?' . $query;
        }

        $request = new Request('GET', $url, $headers);

        return $this->sendRequest($request);
    }

    /**
     * @param string $url
     * @param string|array $data [optional]
     * @param array $headers [optional]
     * @return ResponseInterface
     */
    public function post(string $url, $data = '', array $headers = []): ResponseInterface
    {
        $body = new StringStream(is_array($data) ? http_build_query($data) : $data);
        $request = new Request('POST', $url, $headers, $body);

        return $this->sendRequest($request);
    }

    public function sendRequest(Request $request): ResponseInterface
    {
        $connection = $this->createConnection($request);

        return $this->getResponseStack($connection);
    }

    private function createConnection(Request $request)
    {
        $context = $this->getContext(
            $request->getMethod(),
            $request->getBody()->getContents(),
            $request->getHeaders()
        );

        return fopen(
            $request->getUri(),
            'rb',
            false,
            $context
        );
    }

    /**
     * @param string $method
     * @param string $data [optional]
     * @param array $headers [optional]
     * @return resource
     */
    private function getContext(string $method, string $data = '', array $headers = [])
    {
        $transformedHeaders = $this->getTransformedHeaders($headers);
        $options = [
            'http' => [
                'method' => $method,
                'header' => implode("\r\n", $transformedHeaders),
                'content' => $data
            ]
        ];

        return stream_context_create($options);
    }

    /**
     * @param resource $connection
     * @return ResponseStack
     */
    private function getResponseStack($connection): ResponseStack
    {
        $responseStack = new ResponseStack();
        $metadata = stream_get_meta_data($connection);

        foreach ($this->normalizeHeaders($metadata['wrapper_data']) as $headers) {
            $statusCode = $headers['status'];
            unset($headers['status']);

            $responseStack->add(new Response($statusCode, $headers, new ResourceStream($connection)));
        }

        return $responseStack;
    }

    private function normalizeHeaders(array $wrapperData): array
    {
        $headers = [];

        $index = 0;
        foreach ($wrapperData as $header) {
            if (strpos($header, 'HTTP') === 0) {
                if (count($headers) > 0) {
                    $index++;
                }

                [, $status] = explode(' ', $header);
                $headers[$index]['status'] = (int)$status;

                continue;
            }

            [$key, $value] = explode(':', $header, 2);

            $headers[$index][$key] = $value;
        }

        return $headers;
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
