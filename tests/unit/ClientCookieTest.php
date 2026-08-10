<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Tests\Unit;

use GuzzleHttp\Psr7\HttpFactory;
use GuzzleHttp\Psr7\Response;
use JmapClient\Authentication\Basic;
use JmapClient\Client;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Test cases for the in-memory cookie jar carried across requests
 */
class ClientCookieTest extends TestCase
{
    private ClientInterface $http;

    /**
     * Build a client backed by a spy transport that records outgoing requests
     * and replies with the queued responses in order.
     */
    private function client(ResponseInterface ...$responses): Client
    {
        $this->http = new class (...$responses) implements ClientInterface {
            /** @var RequestInterface[] */
            public array $recorded = [];
            /** @var ResponseInterface[] */
            private array $queue;

            public function __construct(ResponseInterface ...$queue)
            {
                $this->queue = $queue;
            }

            public function sendRequest(RequestInterface $request): ResponseInterface
            {
                $this->recorded[] = $request;
                return array_shift($this->queue) ?? new Response(200, [], '{}');
            }
        };

        $factory = new HttpFactory();
        return new Client('jmap.example.com', new Basic('user', 'secret'), $this->http, $factory, $factory);
    }

    /**
     * A cookie handed out by the server is replayed on the next request
     */
    public function testCarriesCookiesAcrossRequestsWhenEnabled(): void
    {
        $client = $this->client(
            new Response(200, ['Set-Cookie' => 'sid=abc; Path=/'], '{}'),
            new Response(200, [], '{}'),
        );
        $client->configureTransportCookies(true);

        $client->transceive('GET', 'https://jmap.example.com/first');
        $client->transceive('GET', 'https://jmap.example.com/second');

        $this->assertCount(2, $this->http->recorded);
        $this->assertFalse($this->http->recorded[0]->hasHeader('Cookie'));
        $this->assertSame('sid=abc', $this->http->recorded[1]->getHeaderLine('Cookie'));
    }

    /**
     * Without opting in, no cookie jar is kept and nothing is replayed
     */
    public function testDoesNotCarryCookiesByDefault(): void
    {
        $client = $this->client(
            new Response(200, ['Set-Cookie' => 'sid=abc; Path=/'], '{}'),
            new Response(200, [], '{}'),
        );

        $client->transceive('GET', 'https://jmap.example.com/first');
        $client->transceive('GET', 'https://jmap.example.com/second');

        $this->assertCount(2, $this->http->recorded);
        $this->assertFalse($this->http->recorded[1]->hasHeader('Cookie'));
    }

    /**
     * Disabling cookie handling drops the jar so nothing is replayed afterwards
     */
    public function testDisablingCookiesDropsTheJar(): void
    {
        $client = $this->client(
            new Response(200, ['Set-Cookie' => 'sid=abc; Path=/'], '{}'),
            new Response(200, [], '{}'),
        );
        $client->configureTransportCookies(true);
        $client->transceive('GET', 'https://jmap.example.com/first');

        $client->configureTransportCookies(false);
        $client->transceive('GET', 'https://jmap.example.com/second');

        $this->assertFalse($this->http->recorded[1]->hasHeader('Cookie'));
    }
}
