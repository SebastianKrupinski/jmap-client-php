<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Tests\Unit\Requests\Contacts;

use JmapClient\Requests\Contacts\ContactAddressParameters;
use PHPUnit\Framework\TestCase;

class ContactAddressParametersTest extends TestCase
{
    private object $parameters;

    private ContactAddressParameters $address;

    protected function setUp(): void
    {
        $parameters = null;

        $this->address = new ContactAddressParameters($parameters);
        $this->address->bind($parameters);
        $this->parameters = $parameters;
    }

    public function testContext(): void
    {
        $this->address->context('private', 'billing');

        $this->assertEquals((object) [
            'private' => true,
            'billing' => true,
        ], $this->parameters->contexts);
    }

    public function testContextEmpty(): void
    {
        $this->address->context();

        $this->assertEquals(new \stdClass(), $this->parameters->contexts);
    }
}
