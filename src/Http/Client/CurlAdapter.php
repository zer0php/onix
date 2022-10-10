<?php

declare(strict_types=1);

namespace Onix\Http\Client;

use Onix\Http\Exception\NetworkException;
use Onix\Http\Request;
use Onix\Http\Response;
use Onix\Http\Response\ResponseStack;
use Onix\Http\Stream\ResourceStream;

class CurlAdapter implements AdapterInterface
{
    use HeadersNormalizerTrait;

    private array $responseHeaders;
    /**
     * @var resource
     */
    private $responseBody;

    public function createConnection(Request $request)
    {
        $this->responseHeaders = [];
        $this->responseBody = fopen('php://temp', 'w+');

        $connection = curl_init($request->getUri());
        curl_setopt_array($connection, $this->getCurlOptions($request));

        return $connection;
    }

    public function getResponseStack($connection): ResponseStack
    {
        $this->sendCurlRequest($connection);

        rewind($this->responseBody);

        $responseStack = new ResponseStack();
        foreach ($this->normalizeHeaders($this->responseHeaders) as $headers) {
            $statusCode = $headers['status'];
            unset($headers['status']);

            $responseStack->add(
                new Response(
                    $statusCode,
                    $headers,
                    new ResourceStream($this->responseBody)
                )
            );
        }

        return $responseStack;
    }

    private function getRequestHeaders(Request $request): array
    {
        $headers = [];

        foreach ($request->getHeaders() as $name => $values) {
            $headers[] = $name . ': ' . implode(', ', (array)$values);
        }

        return $headers;
    }

    protected function writeResponseHeader($connection, string $header): int
    {
        $trimmedHeader = trim($header);
        if ($trimmedHeader !== '') {
            $this->responseHeaders[] = $trimmedHeader;
        }

        return strlen($header);
    }

    protected function writeResponseBody($connection, string $bodyChunk): int
    {
        return fwrite($this->responseBody, $bodyChunk);
    }

    private function getCurlOptions(Request $request): array
    {
        $options = [
            CURLOPT_CUSTOMREQUEST => $request->getMethod(),
            CURLOPT_HTTPHEADER => $this->getRequestHeaders($request),
            CURLOPT_HEADERFUNCTION => [$this, 'writeResponseHeader'],
            CURLOPT_WRITEFUNCTION => [$this, 'writeResponseBody'],
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_AUTOREFERER => true,
        ];

        $requestBody = $request->getBody()->getContents();
        if ($requestBody !== '') {
            $options[CURLOPT_POSTFIELDS] = $requestBody;
        }

        $userAgent = $request->getHeaders()['User-Agent'] ?? null;
        if ($userAgent !== null) {
            $options[CURLOPT_USERAGENT] = $userAgent;
        }

        return $options;
    }

    private function sendCurlRequest($connection): void
    {
        curl_exec($connection);

        if (curl_errno($connection)) {
            throw new NetworkException(curl_error($connection));
        }

        curl_close($connection);
    }
}
