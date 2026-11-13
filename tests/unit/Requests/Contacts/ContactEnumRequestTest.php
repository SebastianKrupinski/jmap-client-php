<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Tests\Unit\Requests\Contacts;

use JmapClient\Enums\Contacts\ContactAddressComponentKind;
use JmapClient\Enums\Contacts\ContactAnniversaryKind;
use JmapClient\Enums\Contacts\ContactContext;
use JmapClient\Enums\Contacts\ContactGrammaticalGender;
use JmapClient\Enums\Contacts\ContactKind;
use JmapClient\Enums\Contacts\ContactNameComponentKind;
use JmapClient\Enums\Contacts\ContactPhoneFeature;
use JmapClient\Enums\Contacts\ContactPhoneticSystem;
use JmapClient\Enums\Contacts\ContactResourceKind;
use JmapClient\Enums\Contacts\ContactTitleKind;
use JmapClient\Requests\Contacts\ContactQuery;
use JmapClient\Requests\Contacts\ContactSet;
use JmapClient\Requests\RequestBundle;
use PHPUnit\Framework\TestCase;

class ContactEnumRequestTest extends TestCase
{
    private function card(ContactSet $request): array
    {
        return json_decode((new RequestBundle($request))->jsonEncode(), true, 512, JSON_THROW_ON_ERROR)['methodCalls'][0][1]['create']['c1'];
    }

    public function testCardKindAndFilter(): void
    {
        $request = new ContactSet('account-1');
        $request->create('c1')->kind(ContactKind::Org);
        $query = new ContactQuery('account-1');
        $query->filter()->kind(ContactKind::Group);

        $this->assertSame('org', $this->card($request)['kind']);
        $this->assertSame(['kind' => 'group'], json_decode((new RequestBundle($query))->jsonEncode(), true)['methodCalls'][0][1]['filter']);
    }

    public function testNameAndAddressComponents(): void
    {
        $request = new ContactSet('account-1');
        $card = $request->create('c1');
        $card->name()->components()->kind(ContactNameComponentKind::Given)->value('Ada');
        $card->name()->components()->kind(ContactNameComponentKind::Surname)->value('Lovelace');
        $address = $card->addresses('a1');
        $address->components()->kind(ContactAddressComponentKind::Locality)->value('London');
        $address->components()->kind('x-ward')->value('Marylebone');
        $address->context(ContactContext::Private, ContactContext::Billing);
        $address->phoneticSystem(ContactPhoneticSystem::Ipa);

        $data = $this->card($request);

        $this->assertSame(['given', 'surname'], array_column($data['name']['components'], 'kind'));
        $this->assertSame(['locality', 'x-ward'], array_column($data['addresses']['a1']['components'], 'kind'));
        $this->assertSame(['private' => true, 'billing' => true], $data['addresses']['a1']['contexts']);
        $this->assertSame('ipa', $data['addresses']['a1']['phoneticSystem']);
    }

    public function testPhoneEmailAndCustomValues(): void
    {
        $request = new ContactSet('account-1');
        $card = $request->create('c1');
        $card->phones('p1')->context(ContactContext::Work)->features(ContactPhoneFeature::Mobile, ContactPhoneFeature::Text, 'x-satellite');
        $card->emails('e1')->context('x-school');

        $data = $this->card($request);

        $this->assertSame(['work' => true], $data['phones']['p1']['contexts']);
        $this->assertSame(['mobile' => true, 'text' => true, 'x-satellite' => true], $data['phones']['p1']['features']);
        $this->assertSame(['x-school' => true], $data['emails']['e1']['contexts']);
    }

    public function testAnniversaryTitleResourceAndGender(): void
    {
        $request = new ContactSet('account-1');
        $card = $request->create('c1');
        $card->anniversaries('n1')->kind(ContactAnniversaryKind::Birth);
        $card->titles('t1')->kind(ContactTitleKind::Role);
        $card->media('m1')->kind(ContactResourceKind::Photo);
        $card->speakToAs()->grammaticalGender(ContactGrammaticalGender::Neuter);
        $card->speakToAs()->pronouns('r1')->contexts(ContactContext::Private);

        $data = $this->card($request);

        $this->assertSame('birth', $data['anniversaries']['n1']['kind']);
        $this->assertSame('role', $data['titles']['t1']['kind']);
        $this->assertSame('photo', $data['media']['m1']['kind']);
        $this->assertSame('neuter', $data['speakToAs']['grammaticalGender']);
        $this->assertSame(['private' => true], $data['speakToAs']['pronouns']['r1']['contexts']);
    }
}
