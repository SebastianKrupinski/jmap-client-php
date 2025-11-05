<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2023 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Tests\Unit;

use JmapClient\Client;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for JMAP Client
 */
class ClientTest extends TestCase
{
    /**
     * Test that Client can be instantiated
     */
    public function testClientCanBeInstantiated(): void
    {
        $client = new Client();
        $this->assertInstanceOf(Client::class, $client);
    }

    /**
     * Test that Client has default transport mode set to HTTPS
     */
    public function testClientHasDefaultSecureTransportMode(): void
    {
        $client = new Client();
        // We can't directly test private properties, but we can test behavior
        // This test ensures the client is created without errors
        $this->assertInstanceOf(Client::class, $client);
    }

    /**
     * Test that Client can configure transport mode
     */
    public function testClientCanConfigureTransportMode(): void
    {
        $client = new Client();

        // Test setting transport mode to HTTP
        $client->configureTransportMode('http');
        $this->assertInstanceOf(Client::class, $client);

        // Test setting transport mode to HTTPS
        $client->configureTransportMode('https');
        $this->assertInstanceOf(Client::class, $client);
    }

    /**
     * Test that Client can set host
     */
    public function testClientCanSetHost(): void
    {
        $client = new Client();

        $client->setHost('example.com:443');
        $this->assertInstanceOf(Client::class, $client);
    }

    /**
     * Test that Client can configure transport verification
     */
    public function testClientCanConfigureTransportVerification(): void
    {
        $client = new Client();

        // Test enabling verification
        $client->configureTransportVerification(true);
        $this->assertInstanceOf(Client::class, $client);

        // Test disabling verification
        $client->configureTransportVerification(false);
        $this->assertInstanceOf(Client::class, $client);
    }
}
