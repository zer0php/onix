<?php

declare(strict_types=1);

namespace Onix\Http;

use Onix\Http\Client\CookieJar;
use Onix\Http\Client\NativeAdapter;
use Onix\Http\Stream\StringStream;
use ErrorException;

class Client implements ClientInterface
{
    private NativeAdapter $adapter;
    private array $options;

    private ?CookieJar $cookieJar = null;

    public function __construct(NativeAdapter $adapter, array $options = [])
    {
        $this->adapter = $adapter;
        if (isset($options['jar'])) {
            $this->setCookieJar($options['jar']);
            unset($options['jar']);
        }

        $this->options = $options;
    }

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
        if (is_array($data)) {
            $data = http_build_query($data);
            $headers['Content-Type'] = 'application/x-www-form-urlencoded; charset=utf-8';
        }

        $body = new StringStream($data);
        $request = new Request('POST', $url, $headers, $body);

        return $this->sendRequest($request);
    }

    public function sendRequest(Request $request): ResponseInterface
    {
        $request = $this->loadCookieFromJar($request);

        $connection = $this->adapter->createConnection($request);
        $responseStack = $this->adapter->getResponseStack($connection);
        $statusCode = $responseStack->getStatusCode();

        if ($statusCode < 200 || $statusCode > 399) {
            throw new ErrorException($responseStack->getBody()->getContents());
        }

        foreach ($responseStack as $response) {
            $this->addToCookieJarFromHeaders($response);
        }

        return $responseStack;
    }

    private function loadCookieFromJar(Request $request): Request
    {
        if ($this->cookieJar === null) {
            return $request;
        }

        $cookies = [];
        $headers = $request->getHeaders();
        if (isset($headers['Cookie'])) {
            $cookies[] = $headers['Cookie'];
        }
        $cookieLine = $this->cookieJar->getCookieLine();
        if ($cookieLine !== '') {
            $cookies[] = $cookieLine;
        }

        if (count($cookies) === 0) {
            return $request;
        }

        return $request->withHeader('Cookie', implode('; ', $cookies));
    }

    private function addToCookieJarFromHeaders(ResponseInterface $response): void
    {
        if ($this->cookieJar === null) {
            return;
        }

        $headers = $response->getHeaders();
        if (isset($headers['set-cookie'])) {
            $this->addToCookieJar($headers['set-cookie']);
        }
    }

    private function addToCookieJar(array $cookies): void
    {
        foreach ($cookies as $cookie) {
            $cookieQuery = str_replace('; ', '&', $cookie);
            $parsedCookie = [];
            parse_str($cookieQuery, $parsedCookie);

            $this->cookieJar->addCookie(key($parsedCookie), current($parsedCookie));
        }
    }

    private function setCookieJar(CookieJar $jar): void
    {
        $this->cookieJar = $jar;
    }
}
