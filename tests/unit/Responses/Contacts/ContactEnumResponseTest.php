<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Tests\Unit\Responses\Contacts;

use JmapClient\Enums\Contacts\ContactAddressComponentKind;
use JmapClient\Enums\Contacts\ContactContext;
use JmapClient\Enums\Contacts\ContactGrammaticalGender;
use JmapClient\Enums\Contacts\ContactKind;
use JmapClient\Enums\Contacts\ContactNameComponentKind;
use JmapClient\Enums\Contacts\ContactPhoneFeature;
use JmapClient\Enums\Contacts\ContactResourceKind;
use JmapClient\Enums\Contacts\ContactTitleKind;
use JmapClient\Responses\Contacts\ContactAddressParameters;
use JmapClient\Responses\Contacts\ContactNameParameters;
use JmapClient\Responses\Contacts\ContactParameters;
use JmapClient\Responses\Contacts\ContactPhoneParameters;
use JmapClient\Responses\Contacts\ContactResourceParameters;
use JmapClient\Responses\Contacts\ContactSpeakToAsParameters;
use JmapClient\Responses\Contacts\ContactTitleParameters;
use PHPUnit\Framework\TestCase;

class ContactEnumResponseTest extends TestCase
{
    public function testCardKindDefaultsToIndividual(): void
    {
        $this->assertSame(ContactKind::Device, (new ContactParameters(['kind' => 'device']))->kind());
        $this->assertSame('x-robot', (new ContactParameters(['kind' => 'x-robot']))->kind());
        $this->assertSame(ContactKind::Individual, (new ContactParameters([]))->kind());
    }

    public function testComponentKindsUseTheirOwnEnum(): void
    {
        $name = new ContactNameParameters(['components' => [['kind' => 'given', 'value' => 'Ada'], ['kind' => 'separator', 'value' => ' ']]]);
        $address = new ContactAddressParameters(['components' => [['kind' => 'postcode', 'value' => 'NW1'], ['kind' => 'separator', 'value' => ', '], ['kind' => 'x-ward', 'value' => 'M']]]);

        $this->assertSame([ContactNameComponentKind::Given, ContactNameComponentKind::Separator], array_map(fn ($c) => $c->kind(), $name->components()));
        $this->assertSame(
            [ContactAddressComponentKind::Postcode, ContactAddressComponentKind::Separator, 'x-ward'],
            array_map(fn ($c) => $c->kind(), $address->components())
        );
    }

    public function testContextsAndFeaturesReturnEnumsOrCustomStrings(): void
    {
        $phone = new ContactPhoneParameters(['contexts' => ['work' => true, 'x-school' => true], 'features' => ['mobile' => true, 'main-number' => true]]);

        $this->assertSame([ContactContext::Work, 'x-school'], $phone->context());
        $this->assertSame([ContactPhoneFeature::Mobile, ContactPhoneFeature::MainNumber], $phone->features());
    }

    public function testTitleResourceAndGender(): void
    {
        $this->assertSame(ContactTitleKind::Title, (new ContactTitleParameters(['kind' => 'title']))->kind());
        $this->assertSame(ContactResourceKind::FreeBusy, (new ContactResourceParameters(['kind' => 'freeBusy']))->kind());
        $this->assertSame(ContactGrammaticalGender::Feminine, (new ContactSpeakToAsParameters(['grammaticalGender' => 'feminine']))->grammaticalGender());
        $this->assertNull((new ContactSpeakToAsParameters([]))->grammaticalGender());
    }
}
