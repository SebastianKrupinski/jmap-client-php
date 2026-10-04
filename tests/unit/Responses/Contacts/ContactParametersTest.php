<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Tests\Unit\Responses\Contacts;

use JmapClient\Responses\Contacts\ContactParameters;
use PHPUnit\Framework\TestCase;

class ContactParametersTest extends TestCase
{
    public function testInNumericIds(): void
    {
        $contact = new ContactParameters(json_decode('{"addressbookIds":{"1":true,"b":true}}', true));

        $this->assertSame(['1', 'b'], $contact->in());
    }

    public function testInMissing(): void
    {
        $this->assertNull((new ContactParameters([]))->in());
    }

    public function testTagsNumeric(): void
    {
        $contact = new ContactParameters(json_decode('{"keywords":{"2024":true,"VIP":true}}', true));

        $this->assertSame(['2024', 'VIP'], $contact->tags());
    }

    public function testTagsEmpty(): void
    {
        $this->assertNull((new ContactParameters(['keywords' => []]))->tags());
        $this->assertNull((new ContactParameters([]))->tags());
    }
}
