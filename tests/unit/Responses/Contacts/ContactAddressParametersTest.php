<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Tests\Unit\Responses\Contacts;

use JmapClient\Responses\Contacts\ContactAddressParameters;
use PHPUnit\Framework\TestCase;

class ContactAddressParametersTest extends TestCase
{
    public function testContext(): void
    {
        $address = new ContactAddressParameters([
            'contexts' => ['private' => true, 'billing' => true],
        ]);

        $this->assertSame(['private', 'billing'], $address->context());
    }

    public function testContextMissing(): void
    {
        $this->assertSame([], (new ContactAddressParameters([]))->context());
    }

    public function testCountry(): void
    {
        $address = new ContactAddressParameters(['countryCode' => 'US']);

        $this->assertSame('US', $address->country());
    }
}
