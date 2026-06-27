<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Tests\Unit\Transport;

use JmapClient\Transport\CookieJar;
use PHPUnit\Framework\TestCase;

class CookieJarTest extends TestCase
{
    public function testEmptyJarProducesNoHeader(): void
    {
        $jar = new CookieJar();

        $this->assertTrue($jar->isEmpty());
        $this->assertSame('', $jar->toRequestHeader('example.com', '/', true));
    }

    public function testStoresAndEmitsCookie(): void
    {
        $jar = new CookieJar();
        $jar->fromSetCookie('session=abc123; Path=/; Secure', 'example.com');
        $jar->fromSetCookie('lang=en; Path=/', 'example.com');

        $this->assertFalse($jar->isEmpty());
        $this->assertSame('session=abc123; lang=en', $jar->toRequestHeader('example.com', '/', true));
    }

    public function testSecureCookieNotSentOverPlainHttp(): void
    {
        $jar = new CookieJar();
        $jar->fromSetCookie('session=abc123; Path=/; Secure', 'example.com');

        $this->assertSame('', $jar->toRequestHeader('example.com', '/', false));
    }

    public function testPathScoping(): void
    {
        $jar = new CookieJar();
        $jar->fromSetCookie('scoped=1; Path=/api', 'example.com');

        $this->assertSame('scoped=1', $jar->toRequestHeader('example.com', '/api/session', false));
        $this->assertSame('', $jar->toRequestHeader('example.com', '/other', false));
    }

    public function testRoundTripThroughArray(): void
    {
        $jar = new CookieJar();
        $jar->fromSetCookie('session=abc123; Domain=example.com; Path=/; Secure', 'example.com');

        $restored = CookieJar::fromArray($jar->toArray());

        $this->assertSame('session=abc123', $restored->toRequestHeader('example.com', '/', true));
    }

    public function testLaterCookieReplacesEarlierValue(): void
    {
        $jar = new CookieJar();
        $jar->fromSetCookie('session=old; Path=/', 'example.com');
        $jar->fromSetCookie('session=new; Path=/', 'example.com');

        $this->assertSame('session=new', $jar->toRequestHeader('example.com', '/', false));
    }

    public function testMalformedHeaderIsIgnored(): void
    {
        $jar = new CookieJar();
        $jar->fromSetCookie('not-a-cookie', 'example.com');
        $jar->fromSetCookie('=novalue; Path=/', 'example.com');

        $this->assertTrue($jar->isEmpty());
        $this->assertSame('', $jar->toRequestHeader('example.com', '/', true));
    }
}
