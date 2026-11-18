<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Tests\Unit\Responses\Mail;

use JmapClient\Enums\Mail\MailHeaderForm;
use JmapClient\Responses\Mail\MailPart;
use PHPUnit\Framework\TestCase;

class MailPartTest extends TestCase
{
    private MailPart $part;

    protected function setUp(): void
    {
        $this->part = new MailPart([
            'partId' => '2',
            'blobId' => 'blob-2',
            'type' => 'text/calendar',
            'header:Content-Type:asRaw' => ' text/calendar; method=REQUEST; charset=utf-8',
            'header:Content-Type:asText' => 'text/calendar; method=REQUEST; charset=utf-8',
            'header:X-Test:asRaw:all' => [' first', ' second'],
            'subParts' => [
                ['partId' => '3', 'header:Content-Type:asRaw' => ' text/plain'],
            ],
        ]);
    }

    public function testHeader(): void
    {
        $this->assertSame(' text/calendar; method=REQUEST; charset=utf-8', $this->part->header('Content-Type'));
    }

    public function testHeaderSupportsCustomForm(): void
    {
        $this->assertSame('text/calendar; method=REQUEST; charset=utf-8', $this->part->header('Content-Type', MailHeaderForm::Text));
    }

    public function testHeaderSupportsAllValues(): void
    {
        $this->assertSame([' first', ' second'], $this->part->header('X-Test', MailHeaderForm::Raw, true));
    }

    public function testHeaderReturnsNullWhenNotPresent(): void
    {
        $this->assertNull($this->part->header('Content-Disposition'));
        $this->assertNull($this->part->header('X-Test'));
    }

    public function testHeaderOnSubPart(): void
    {
        $this->assertSame(' text/plain', $this->part->parts()[0]->header('Content-Type'));
    }
}
