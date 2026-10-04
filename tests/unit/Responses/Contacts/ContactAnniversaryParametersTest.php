<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Tests\Unit\Responses\Contacts;

use JmapClient\Responses\Contacts\ContactAddressParameters;
use JmapClient\Responses\Contacts\ContactAnniversaryParameters;
use JmapClient\Responses\Contacts\ContactDatePartialParameters;
use JmapClient\Responses\Contacts\ContactDateStampParameters;
use PHPUnit\Framework\TestCase;

class ContactAnniversaryParametersTest extends TestCase
{
    public function testTimestamp(): void
    {
        $anniversary = new ContactAnniversaryParameters([
            'date' => ['@type' => 'Timestamp', 'utc' => '1980-07-22T00:00:00Z'],
        ]);

        $date = $anniversary->date();
        $this->assertInstanceOf(ContactDateStampParameters::class, $date);
        $this->assertSame('1980-07-22T00:00:00+00:00', $date->value()->format(DATE_ATOM));
    }

    public function testPartialDate(): void
    {
        $anniversary = new ContactAnniversaryParameters([
            'date' => ['month' => 7, 'day' => 22],
        ]);

        $date = $anniversary->date();
        $this->assertInstanceOf(ContactDatePartialParameters::class, $date);
        $this->assertSame(7, $date->month());
        $this->assertSame(22, $date->day());
    }

    public function testDateMissing(): void
    {
        $this->assertNull((new ContactAnniversaryParameters([]))->date());
    }

    public function testKind(): void
    {
        $anniversary = new ContactAnniversaryParameters(['kind' => 'birth']);

        $this->assertSame('birth', $anniversary->kind());
    }

    public function testPlace(): void
    {
        $anniversary = new ContactAnniversaryParameters([
            'place' => ['full' => 'Springfield, USA'],
        ]);

        $place = $anniversary->place();
        $this->assertInstanceOf(ContactAddressParameters::class, $place);
        $this->assertSame('Springfield, USA', $place->full());
    }

    public function testPlaceMissing(): void
    {
        $this->assertNull((new ContactAnniversaryParameters([]))->place());
    }
}
