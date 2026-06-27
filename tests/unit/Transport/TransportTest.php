<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Tests\Unit\Transport;

use GuzzleHttp\Psr7\HttpFactory;
use JmapClient\Exceptions\RequestException;
use JmapClient\Exceptions\TransportException;
use JmapClient\Transport\CookieJar;
use JmapClient\Transport\Transport;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class TransportTest extends TestCase
{
    private HttpFactory $factory;

    protected function setUp(): void
    {
        $this->factory = new HttpFactory();
    }

    private function transport(ClientInterface $client): Transport
    {
        return new Transport($client, $this->factory, $this->factory);
    }

    private function clientReturning(ResponseInterface ...$responses): ClientInterface
    {
        $client = $this->createMock(ClientInterface::class);
        $client->method('sendRequest')->willReturnOnConsecutiveCalls(...$responses);
        return $client;
    }

    public function testSendBuildsRequestWithHeadersAndBody(): void
    {
        $captured = null;
        $client = $this->createMock(ClientInterface::class);
        $client->method('sendRequest')->willReturnCallback(
            function (RequestInterface $request) use (&$captured): ResponseInterface {
                $captured = $request;
                return $this->factory->createResponse(200);
            },
        );

        $this->transport($client)->transceive('POST', 'https://example.com/jmap', ['X-Test' => 'yes'], '{"a":1}');

        $this->assertInstanceOf(RequestInterface::class, $captured);
        $this->assertSame('POST', $captured->getMethod());
        $this->assertSame('yes', $captured->getHeaderLine('X-Test'));
        $this->assertSame('{"a":1}', (string)$captured->getBody());
    }

    public function testThrowsRequestExceptionOnErrorStatus(): void
    {
        $transport = $this->transport($this->clientReturning($this->factory->createResponse(404)));

        $this->expectException(RequestException::class);
        $transport->transceive('GET', 'https://example.com/jmap');
    }

    public function testErrorStatusReturnedWhenHttpErrorsDisabled(): void
    {
        $transport = $this->transport($this->clientReturning($this->factory->createResponse(500)));
        $transport->httpErrors(false);

        $response = $transport->transceive('GET', 'https://example.com/jmap');

        $this->assertSame(500, $response->getStatusCode());
    }

    public function testWrapsClientExceptionAsTransportException(): void
    {
        $client = $this->createMock(ClientInterface::class);
        $client->method('sendRequest')->willThrowException(
            new class ('boom') extends \RuntimeException implements ClientExceptionInterface {},
        );

        $this->expectException(TransportException::class);
        $this->transport($client)->transceive('GET', 'https://example.com/jmap');
    }

    public function testFollowsRedirectWhenEnabled(): void
    {
        $requests = [];
        $client = $this->createMock(ClientInterface::class);
        $client->method('sendRequest')->willReturnCallback(
            function (RequestInterface $request) use (&$requests): ResponseInterface {
                $requests[] = (string)$request->getUri();
                if (count($requests) === 1) {
                    return $this->factory->createResponse(302)
                        ->withHeader('Location', 'https://example.com/final');
                }
                return $this->factory->createResponse(200);
            },
        );

        $response = $this->transport($client)->transceive('GET', 'https://example.com/start', [], null, ['allow_redirects' => true]);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame(['https://example.com/start', 'https://example.com/final'], $requests);
    }

    public function testDoesNotFollowRedirectByDefault(): void
    {
        $transport = $this->transport($this->clientReturning(
            $this->factory->createResponse(302)->withHeader('Location', 'https://example.com/final'),
        ));

        $response = $transport->transceive('GET', 'https://example.com/start');

        $this->assertSame(302, $response->getStatusCode());
    }

    public function testWritesResponseBodyToSink(): void
    {
        $transport = $this->transport($this->clientReturning(
            $this->factory->createResponse(200)->withBody($this->factory->createStream('payload')),
        ));
        $sink = fopen('php://temp', 'r+');

        $transport->transceive('GET', 'https://example.com/blob', [], null, ['sink' => $sink]);

        rewind($sink);
        $this->assertSame('payload', stream_get_contents($sink));
        fclose($sink);
    }

    public function testCookiesAreStoredAndResent(): void
    {
        $cookieHeaders = [];
        $client = $this->createMock(ClientInterface::class);
        $client->method('sendRequest')->willReturnCallback(
            function (RequestInterface $request) use (&$cookieHeaders): ResponseInterface {
                $cookieHeaders[] = $request->getHeaderLine('Cookie');
                return $this->factory->createResponse(200)
                    ->withHeader('Set-Cookie', 'session=abc; Path=/');
            },
        );
        $transport = $this->transport($client);
        $transport->setCookieJar(new CookieJar());

        $transport->transceive('GET', 'https://example.com/login');
        $transport->transceive('GET', 'https://example.com/data');

        $this->assertSame('', $cookieHeaders[0]);
        $this->assertSame('session=abc', $cookieHeaders[1]);
    }

    public function testObserverReceivesRequestAndResponse(): void
    {
        $seen = [];
        $transport = $this->transport($this->clientReturning($this->factory->createResponse(200)));
        $transport->observer(function (RequestInterface $request, ?ResponseInterface $response) use (&$seen): void {
            $seen[] = [$request->getMethod(), $response?->getStatusCode()];
        });

        $transport->transceive('GET', 'https://example.com/jmap');

        $this->assertSame([['GET', 200]], $seen);
    }

    public function testRetainsRequestAndResponseDataWhenEnabled(): void
    {
        $transport = $this->transport($this->clientReturning(
            $this->factory->createResponse(201)
                ->withHeader('X-Resp', 'yes')
                ->withBody($this->factory->createStream('{"ok":true}')),
        ));
        $transport->retainRequestHeader(true);
        $transport->retainRequestBody(true);
        $transport->retainResponseHeader(true);
        $transport->retainResponseBody(true);

        $transport->transceive('POST', 'https://example.com/jmap', ['X-Req' => 'abc'], '{"a":1}');

        $this->assertSame('abc', $transport->requestHeaderData()['X-Req'][0] ?? null);
        $this->assertSame('{"a":1}', $transport->requestBodyData());
        $this->assertSame(201, $transport->responseCode());
        $this->assertSame('yes', $transport->responseHeaderData()['X-Resp'][0] ?? null);
        $this->assertSame('{"ok":true}', $transport->responseBodyData());
    }

    public function testRetainedDataIsResetBetweenSends(): void
    {
        $transport = $this->transport($this->clientReturning(
            $this->factory->createResponse(200)->withBody($this->factory->createStream('first')),
            $this->factory->createResponse(500)->withBody($this->factory->createStream('second')),
        ));
        $transport->httpErrors(false);
        $transport->retainResponseBody(true);

        $transport->transceive('GET', 'https://example.com/a');
        $this->assertSame(200, $transport->responseCode());
        $this->assertSame('first', $transport->responseBodyData());

        $transport->transceive('GET', 'https://example.com/b');
        $this->assertSame(500, $transport->responseCode());
        $this->assertSame('second', $transport->responseBodyData());
    }

    public function testRetainedResponseBodyRemainsReadableByCaller(): void
    {
        $transport = $this->transport($this->clientReturning(
            $this->factory->createResponse(200)->withBody($this->factory->createStream('payload')),
        ));
        $transport->retainResponseBody(true);

        $response = $transport->transceive('GET', 'https://example.com/jmap');

        // retention must not consume the body for the caller
        $this->assertSame('payload', $response->getBody()->getContents());
        $this->assertSame('payload', $transport->responseBodyData());
    }

    public function testWritesLogEntriesWhenEnabled(): void
    {
        $log = tempnam(sys_get_temp_dir(), 'jmaplog');
        $transport = $this->transport($this->clientReturning(
            $this->factory->createResponse(200)->withBody($this->factory->createStream('{"ok":1}')),
        ));
        $transport->logState(true);
        $transport->logLocation($log);

        $transport->transceive('POST', 'https://example.com/jmap', ['Content-Type' => 'application/json'], '{"a":1}');

        $contents = file_get_contents($log);
        unlink($log);
        $this->assertStringContainsString('"type":"request"', $contents);
        $this->assertStringContainsString('"type":"response"', $contents);
        $this->assertStringContainsString('"status":200', $contents);
    }
}
