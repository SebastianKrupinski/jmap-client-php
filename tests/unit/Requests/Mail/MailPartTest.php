<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Tests\Unit\Requests\Mail;

use JmapClient\Requests\Mail\MailPart;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use stdClass;

class MailPartTest extends TestCase
{
    private object $parameters;

    private MailPart $part;

    protected function setUp(): void
    {
        $this->parameters = new stdClass();
        $this->part = new MailPart($this->parameters);
    }

    public function testHeader(): void
    {
        $this->part
            ->blob('blob-1')
            ->header('Content-Type', ' text/calendar; method=REQUEST; charset=utf-8');

        $this->assertSame(' text/calendar; method=REQUEST; charset=utf-8', $this->parameters->{'header:Content-Type:asRaw'});
        $this->assertObjectNotHasProperty('headers', $this->parameters);
        $this->assertObjectNotHasProperty('type', $this->parameters);
    }

    public function testHeaders(): void
    {
        $this->part->headers([
            'Content-Type' => ' text/calendar; method=REPLY',
            'Content-Language' => ' en',
        ]);

        $this->assertSame(' text/calendar; method=REPLY', $this->parameters->{'header:Content-Type:asRaw'});
        $this->assertSame(' en', $this->parameters->{'header:Content-Language:asRaw'});
        $this->assertObjectNotHasProperty('headers', $this->parameters);
    }

    public function testHeaderOnSubPart(): void
    {
        $this->part
            ->type('multipart/mixed')
            ->addPart()
            ->blob('blob-1')
            ->header('Content-Type', ' text/calendar; method=CANCEL');

        $this->assertSame(' text/calendar; method=CANCEL', $this->parameters->subParts[0]->{'header:Content-Type:asRaw'});
    }

    public function testHeaderReplacesSameHeaderInAnyCase(): void
    {
        $this->part
            ->header('content-type', ' text/plain')
            ->header('Content-Type', ' text/calendar; method=REQUEST');

        $this->assertSame(['header:Content-Type:asRaw' => ' text/calendar; method=REQUEST'], get_object_vars($this->parameters));
    }

    public function testHeaderReplacesParsedProperties(): void
    {
        $this->part
            ->type('text/calendar')
            ->charset('utf-8')
            ->name('invite.ics')
            ->disposition('attachment')
            ->cid('cid-1')
            ->language('en')
            ->location('https://example.com/invite.ics');

        $this->part->headers([
            'Content-Type' => ' text/calendar; method=REQUEST; charset=utf-8',
            'Content-Disposition' => ' attachment; filename=invite.ics',
            'Content-ID' => ' <cid-2>',
            'Content-Language' => ' de',
            'Content-Location' => ' https://example.com/other.ics',
        ]);

        $this->assertSame([
            'header:Content-Type:asRaw' => ' text/calendar; method=REQUEST; charset=utf-8',
            'header:Content-Disposition:asRaw' => ' attachment; filename=invite.ics',
            'header:Content-ID:asRaw' => ' <cid-2>',
            'header:Content-Language:asRaw' => ' de',
            'header:Content-Location:asRaw' => ' https://example.com/other.ics',
        ], get_object_vars($this->parameters));
    }

    public static function parsedPropertyProvider(): array
    {
        return [
            'type' => ['type', 'text/plain', 'content-type'],
            'charset' => ['charset', 'utf-8', 'content-type'],
            'disposition' => ['disposition', 'inline', 'content-disposition'],
            'name' => ['name', 'invite.ics', 'content-disposition'],
            'cid' => ['cid', 'cid-1', 'content-id'],
            'language' => ['language', 'en', 'content-language'],
            'location' => ['location', 'https://example.com', 'content-location'],
        ];
    }

    #[DataProvider('parsedPropertyProvider')]
    public function testParsedPropertyReplacesHeader(string $property, string $value, string $header): void
    {
        $this->parameters->{"header:$header:asRaw"} = ' raw';
        $this->parameters->{"header:$header:asText"} = 'text';
        $this->parameters->{'header:X-Other:asRaw'} = ' other';

        $this->part->$property($value);

        $this->assertSame([
            'header:X-Other:asRaw' => ' other',
            $property => $value,
        ], get_object_vars($this->parameters));
    }

    public function testParsedPropertiesSharingHeaderDoNotRemoveEachOther(): void
    {
        $this->part->type('text/plain')->charset('utf-8');

        $this->assertSame([
            'type' => 'text/plain',
            'charset' => 'utf-8',
        ], get_object_vars($this->parameters));
    }
}
