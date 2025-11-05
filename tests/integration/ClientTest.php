<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Tests\Integration;

use JmapClient\Client;
use JmapClient\Session\Session;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    private Client $client;

    public function setUp(): void
    {
        parent::setUp();
        $this->client = ClientFactory::instance('service1');
    }

    public function testConnect(): void
    {
        $session = $this->client->connect();
        $this->assertInstanceOf(Session::class, $session);
    }

}
