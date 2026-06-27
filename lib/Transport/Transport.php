<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Transport;

use JmapClient\Exceptions\RequestException;
use JmapClient\Exceptions\TransportException;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;

class Transport
{
    private bool $httpErrors = true;
    private int $maxRedirects = 5;
    private ?CookieJar $cookies = null;
    /** @var null|callable(RequestInterface, ?ResponseInterface, ?\Throwable): void */
    private $observer = null;
    // Retention configuration
    private bool $retainRequestHeader = false;
    private bool $retainRequestBody = false;
    private bool $retainResponseHeader = false;
    private bool $retainResponseBody = false;
    // Retained data
    private array $requestHeaderData = [];
    private string $requestBodyData = '';
    private array $responseHeaderData = [];
    private string $responseBodyData = '';
    private int $responseCode = 0;
    // Logging configuration
    private bool $logState = false;
    private string $logLocation;

    public function __construct(
        private ClientInterface $client,
        private RequestFactoryInterface $requestFactory,
        private StreamFactoryInterface $streamFactory,
    ) {
        $this->logLocation = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'php-jmap.log';
    }

    public function client(): ClientInterface
    {
        return $this->client;
    }

    public function httpErrors(bool $value): void
    {
        $this->httpErrors = $value;
    }

    public function maxRedirects(int $value): void
    {
        $this->maxRedirects = max(0, $value);
    }

    public function setCookieJar(?CookieJar $jar): void
    {
        $this->cookies = $jar;
    }

    public function getCookieJar(): ?CookieJar
    {
        return $this->cookies;
    }

    /**
     * @param null|callable(RequestInterface, ?ResponseInterface, ?\Throwable): void $observer
     */
    public function observer(?callable $observer): void
    {
        $this->observer = $observer;
    }

    public function retainRequestHeader(bool $value): void
    {
        $this->retainRequestHeader = $value;
    }

    public function retainRequestBody(bool $value): void
    {
        $this->retainRequestBody = $value;
    }

    public function retainResponseHeader(bool $value): void
    {
        $this->retainResponseHeader = $value;
    }

    public function retainResponseBody(bool $value): void
    {
        $this->retainResponseBody = $value;
    }

    public function logState(bool $value): void
    {
        $this->logState = $value;
    }

    public function logLocation(string $value): void
    {
        $this->logLocation = $value;
    }

    public function requestHeaderData(): array
    {
        return $this->requestHeaderData;
    }

    public function requestBodyData(): string
    {
        return $this->requestBodyData;
    }

    public function responseHeaderData(): array
    {
        return $this->responseHeaderData;
    }

    public function responseBodyData(): string
    {
        return $this->responseBodyData;
    }

    public function responseCode(): int
    {
        return $this->responseCode;
    }

    /**
     * Transmits a request and returns the response, following redirects and handling cookies as configured.
     *
     * @param array<string, string> $headers
     * @param string|resource|StreamInterface|null $body
     * @param array{allow_redirects?: bool, sink?: resource} $options
     *
     * @throws RequestException When the response status is >= 400 and error handling is enabled.
     * @throws TransportException When the underlying client cannot deliver the request.
     */
    public function transceive(string $method, string $uri, array $headers = [], $body = null, array $options = []): ResponseInterface
    {
        $followRedirects = (bool)($options['allow_redirects'] ?? false);
        $sink = $options['sink'] ?? null;

        $this->resetRetention();

        $request = $this->buildRequest($method, $uri, $headers, $body);
        $redirectsRemaining = $followRedirects ? $this->maxRedirects : 0;

        while (true) {
            $request = $this->applyCookies($request);

            try {
                $response = $this->client->sendRequest($request);
            } catch (ClientExceptionInterface $e) {
                $this->capture($request, null, $e);
                throw new TransportException($e->getMessage(), (int)$e->getCode(), $e);
            }

            if ($this->cookies !== null) {
                $host = $request->getUri()->getHost();
                foreach ($response->getHeader('Set-Cookie') as $header) {
                    $this->cookies->fromSetCookie($header, $host);
                }
            }
            $this->capture($request, $response, null);

            $status = $response->getStatusCode();
            if ($redirectsRemaining > 0 && $status >= 300 && $status < 400 && $response->hasHeader('Location')) {
                $request = $this->buildRedirect($request, $response);
                $redirectsRemaining--;
                continue;
            }
            break;
        }

        if ($this->httpErrors && $response->getStatusCode() >= 400) {
            throw new RequestException($request, $response);
        }

        if (is_resource($sink)) {
            $this->writeToSink($response->getBody(), $sink);
        }

        return $response;
    }

    /**
     * @param array<string, string> $headers
     * @param string|resource|StreamInterface|null $body
     */
    private function buildRequest(string $method, string $uri, array $headers, $body): RequestInterface
    {
        $request = $this->requestFactory->createRequest($method, $uri);
        foreach ($headers as $name => $value) {
            $request = $request->withHeader($name, $value);
        }
        if ($body !== null && $body !== '') {
            $stream = match (true) {
                $body instanceof StreamInterface => $body,
                is_resource($body) => $this->streamFactory->createStreamFromResource($body),
                default => $this->streamFactory->createStream((string)$body),
            };
            $request = $request->withBody($stream);
        }
        return $request;
    }

    private function buildRedirect(RequestInterface $request, ResponseInterface $response): RequestInterface
    {
        $location = $this->resolveLocation($request, $response->getHeaderLine('Location'));
        $status = $response->getStatusCode();
        $method = $request->getMethod();

        // Non-strict redirect semantics: 303, and 301/302 on POST, become GET
        // without a body; 307/308 preserve the original method and body.
        $downgrade = $status === 303 || (($status === 301 || $status === 302) && $method === 'POST');
        $redirectMethod = $downgrade ? 'GET' : $method;

        $redirect = $this->requestFactory->createRequest($redirectMethod, $location);
        foreach ($request->getHeaders() as $name => $values) {
            if (strcasecmp($name, 'Host') === 0 || strcasecmp($name, 'Cookie') === 0) {
                continue;
            }
            $redirect = $redirect->withHeader($name, $values);
        }
        if (!$downgrade) {
            $body = $request->getBody();
            if ($body->isSeekable()) {
                $body->rewind();
            }
            $redirect = $redirect->withBody($body);
        } else {
            $redirect = $redirect
                ->withoutHeader('Content-Type')
                ->withoutHeader('Content-Length');
        }
        return $redirect;
    }

    private function applyCookies(RequestInterface $request): RequestInterface
    {
        if ($this->cookies === null || $this->cookies->isEmpty()) {
            return $request;
        }
        $uri = $request->getUri();
        $header = $this->cookies->toRequestHeader(
            $uri->getHost(),
            $uri->getPath() !== '' ? $uri->getPath() : '/',
            $uri->getScheme() === 'https',
        );
        if ($header === '') {
            return $request;
        }
        return $request->withHeader('Cookie', $header);
    }

    private function resolveLocation(RequestInterface $request, string $location): string
    {
        if (preg_match('#^https?://#i', $location) === 1) {
            return $location;
        }
        $uri = $request->getUri();
        $base = $uri->getScheme() . '://' . $uri->getAuthority();
        if (str_starts_with($location, '/')) {
            return $base . $location;
        }
        $path = $uri->getPath();
        $directory = substr($path, 0, (int)strrpos($path, '/') + 1);
        return $base . $directory . $location;
    }

    /**
     * @param resource $sink
     */
    private function writeToSink(StreamInterface $body, $sink): void
    {
        if ($body->isSeekable()) {
            $body->rewind();
        }
        while (!$body->eof()) {
            $chunk = $body->read(8192);
            if ($chunk === '') {
                break;
            }
            fwrite($sink, $chunk);
        }
    }

    private function resetRetention(): void
    {
        $this->requestHeaderData = [];
        $this->requestBodyData = '';
        $this->responseHeaderData = [];
        $this->responseBodyData = '';
        $this->responseCode = 0;
    }

    /**
     * Retains and/or logs the request and response according to configuration
     */
    private function capture(RequestInterface $request, ?ResponseInterface $response, ?\Throwable $error): void
    {
        // generate unique transaction ID for pairing request/response
        $transactionId = bin2hex(random_bytes(8));

        // retain request headers
        if ($this->retainRequestHeader) {
            $this->requestHeaderData = $request->getHeaders();
        }
        // retain/log request body
        if ($this->retainRequestBody || $this->logState) {
            [$message, $contentType] = $this->captureContents($request);
            if ($this->retainRequestBody && $message !== null) {
                $this->requestBodyData = $message;
            }
            if ($this->logState) {
                $this->log('request', $message, null, $request->getMethod(), (string)$request->getUri(), $contentType, $transactionId);
            }
        }

        // a null response indicates a transport-level failure with no response
        if ($response === null) {
            return;
        }

        // retain response code
        $this->responseCode = $response->getStatusCode();
        // retain response header
        if ($this->retainResponseHeader) {
            $this->responseHeaderData = $response->getHeaders();
        }
        // retain/log response body
        if ($this->retainResponseBody || $this->logState) {
            [$message, $contentType] = $this->captureContents($response);
            if ($this->retainResponseBody && $message !== null) {
                $this->responseBodyData = $message;
            }
            if ($this->logState) {
                $this->log('response', $message, $response->getStatusCode(), $request->getMethod(), (string)$request->getUri(), $contentType, $transactionId);
            }
        }

        if ($this->observer !== null) {
            ($this->observer)($request, $response, $error);
        }
    }

    private function captureContents(MessageInterface $message): array
    {
        $body = $message->getBody();
        if (!$body->isSeekable()) {
            return ['resource stream', null];
        }
        try {
            $content = $body->getContents();
            $body->rewind();
        } catch (\Throwable $e) {
            return [null, null];
        }
        if ($content === '') {
            return [null, null];
        }
        return [$content, $message->getHeaderLine('Content-Type')];
    }

    private function log(string $type, ?string $message, ?int $statusCode = null, ?string $method = null, ?string $url = null, ?string $contentType = null, ?string $transactionId = null): void
    {
        // build log entry with date and type
        $logData = [
            'date' => date('Y-m-d H:i:s.') . gettimeofday()['usec'],
            'type' => $type,
        ];

        // add transaction ID for pairing request/response
        if ($transactionId !== null) {
            $logData['transaction'] = $transactionId;
        }

        // add method and URL for both request and response
        if ($method !== null) {
            $logData['method'] = $method;
        }
        if ($url !== null) {
            $logData['url'] = $url;
        }

        // add status code for response
        if ($statusCode !== null) {
            $logData['status'] = $statusCode;
        }

        // add the message content if present
        if ($message !== null && $message !== '') {
            // check if content type indicates JSON
            $isJson = false;
            if ($contentType !== null && (
                strpos($contentType, 'application/json') !== false ||
                strpos($contentType, 'application/jmap+json') !== false
            )) {
                $isJson = true;
            }

            // if it's JSON content type, try to decode and keep as object/array for prettier logging
            if ($isJson) {
                $decoded = json_decode($message, true);
                if ($decoded !== null) {
                    $logData['data'] = $decoded;
                } else {
                    // fallback if JSON decode fails
                    $logData['data'] = $message;
                }
            } else {
                // for non-JSON, store as string
                $logData['data'] = $message;
            }
        }

        // encode to JSON and write to log file
        $logEntry = json_encode($logData, JSON_UNESCAPED_SLASHES) . PHP_EOL;

        // write to log file
        file_put_contents($this->logLocation, $logEntry, FILE_APPEND);
    }
}
