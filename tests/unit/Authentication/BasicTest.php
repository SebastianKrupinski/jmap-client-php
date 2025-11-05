<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Tests\Unit\Authentication;

use JmapClient\Authentication\Basic;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for Basic Authentication
 */
class BasicTest extends TestCase
{
    /**
     * Test that Basic authentication can be instantiated
     */
    public function testBasicCanBeInstantiated(): void
    {
        $auth = new Basic();
        $this->assertInstanceOf(Basic::class, $auth);
    }

    /**
     * Test that Basic authentication can be instantiated with parameters
     */
    public function testBasicCanBeInstantiatedWithParameters(): void
    {
        $auth = new Basic('user@example.com', 'password', 'example.com');
        $this->assertInstanceOf(Basic::class, $auth);
    }

    /**
     * Test getter and setter for Id
     */
    public function testIdGetterAndSetter(): void
    {
        $auth = new Basic();

        // Test initial value is null
        $this->assertNull($auth->getId());

        // Test setting a value
        $auth->setId('user@example.com');
        $this->assertEquals('user@example.com', $auth->getId());

        // Test setting null
        $auth->setId(null);
        $this->assertNull($auth->getId());
    }

    /**
     * Test getter and setter for Secret
     */
    public function testSecretGetterAndSetter(): void
    {
        $auth = new Basic();

        // Test initial value is null
        $this->assertNull($auth->getSecret());

        // Test setting a value
        $auth->setSecret('password123');
        $this->assertEquals('password123', $auth->getSecret());

        // Test setting null
        $auth->setSecret(null);
        $this->assertNull($auth->getSecret());
    }

    /**
     * Test getter and setter for Location
     */
    public function testLocationGetterAndSetter(): void
    {
        $auth = new Basic();

        // Test initial value is null
        $this->assertNull($auth->getLocation());

        // Test setting a value
        $auth->setLocation('example.com');
        $this->assertEquals('example.com', $auth->getLocation());

        // Test setting null
        $auth->setLocation(null);
        $this->assertNull($auth->getLocation());
    }

    /**
     * Test constructor with all parameters
     */
    public function testConstructorWithAllParameters(): void
    {
        $auth = new Basic('user@example.com', 'password', 'example.com');

        $this->assertEquals('user@example.com', $auth->getId());
        $this->assertEquals('password', $auth->getSecret());
        $this->assertEquals('example.com', $auth->getLocation());
    }
}
