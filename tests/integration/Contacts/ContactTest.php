<?php

declare(strict_types=1);
/**
 * SPDX-FileCopyrightText: Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Tests\Integration\Contacts;

use JmapClient\Client;
use JmapClient\Requests\Contacts\AddressBookSet as AddressBookSetRequest;
use JmapClient\Requests\Contacts\ContactGet as ContactGetRequest;
use JmapClient\Requests\Contacts\ContactQuery as ContactQueryRequest;
use JmapClient\Requests\Contacts\ContactSet as ContactSetRequest;
use JmapClient\Responses\Contacts\ContactGet as ContactGetResponse;
use JmapClient\Responses\Contacts\ContactParameters;
use JmapClient\Responses\Contacts\ContactQuery as ContactQueryResponse;
use JmapClient\Responses\Contacts\ContactSet as ContactSetResponse;
use JmapClient\Session\Account;
use JmapClient\Tests\Integration\ClientFactory;
use PHPUnit\Framework\TestCase;

class ContactTest extends TestCase
{
    private Client $client;
    private Account $account;
    private string $addressBookId;

    public function setUp(): void
    {
        parent::setUp();
        $this->client = ClientFactory::instance('service1');
        $this->client->connect();
        $this->account = $this->client->sessionAccountDefault('contacts');

        // construct address book create request
        $bookId = uniqid();
        $r0 = new AddressBookSetRequest($this->account->id());
        $p0 = $r0->create($bookId);
        $p0->label('Test Contact Address Book ' . time());

        // transceive
        $bundle = $this->client->perform([$r0]);

        // extract address book id
        $result = $bundle->first()->createSuccess($bookId);
        $this->addressBookId = $result['id'];
    }

    public function tearDown(): void
    {
        parent::tearDown();
        // construct address book delete request (removes all contained contacts)
        $r0 = new AddressBookSetRequest($this->account->id());
        $r0->delete($this->addressBookId);
        $r0->deleteContents(true);

        // transceive
        $this->client->perform([$r0]);
    }

    private function generateContactData(string $key): array
    {
        return match ($key) {
            'minimal' => [
                'name' => [
                    'full' => 'Minimal Contact',
                    'given' => 'Minimal',
                    'surname' => 'Contact',
                ],
                'emails' => null,
                'phones' => null,
                'addresses' => null,
                'organizations' => null,
                'notes' => null,
            ],
            'with_email' => [
                'name' => [
                    'full' => 'Email Contact',
                    'given' => 'Email',
                    'surname' => 'Contact',
                ],
                'emails' => [
                    ['address' => 'email.contact@example.com', 'context' => 'work'],
                ],
                'phones' => null,
                'addresses' => null,
                'organizations' => null,
                'notes' => null,
            ],
            'with_phone' => [
                'name' => [
                    'full' => 'Phone Contact',
                    'given' => 'Phone',
                    'surname' => 'Contact',
                ],
                'emails' => null,
                'phones' => [
                    ['number' => '+1-555-000-0001', 'context' => 'work'],
                ],
                'addresses' => null,
                'organizations' => null,
                'notes' => null,
            ],
            'with_address' => [
                'name' => [
                    'full' => 'Address Contact',
                    'given' => 'Address',
                    'surname' => 'Contact',
                ],
                'emails' => null,
                'phones' => null,
                'addresses' => [
                    ['full' => '123 Main St, Springfield, USA', 'country' => 'US'],
                ],
                'organizations' => null,
                'notes' => null,
            ],
            'with_organization' => [
                'name' => [
                    'full' => 'Organization Contact',
                    'given' => 'Organization',
                    'surname' => 'Contact',
                ],
                'emails' => null,
                'phones' => null,
                'addresses' => null,
                'organizations' => [
                    ['name' => 'ACME Corp'],
                ],
                'notes' => null,
            ],
            'with_note' => [
                'name' => [
                    'full' => 'Note Contact',
                    'given' => 'Note',
                    'surname' => 'Contact',
                ],
                'emails' => null,
                'phones' => null,
                'addresses' => null,
                'organizations' => null,
                'notes' => [
                    ['contents' => 'This is a test note for the contact.'],
                ],
            ],
            'full' => [
                'name' => [
                    'full' => 'Full Contact',
                    'given' => 'Full',
                    'surname' => 'Contact',
                ],
                'emails' => [
                    ['address' => 'full.contact@example.com', 'context' => 'work'],
                    ['address' => 'full.contact.home@example.com', 'context' => 'private'],
                ],
                'phones' => [
                    ['number' => '+1-555-000-0002', 'context' => 'work'],
                    ['number' => '+1-555-000-0003', 'context' => 'private'],
                ],
                'addresses' => [
                    ['full' => '456 Business Ave, Metropolis, USA', 'country' => 'US'],
                ],
                'organizations' => [
                    ['name' => 'Full Corp'],
                ],
                'notes' => [
                    ['contents' => 'A fully populated contact for testing.'],
                ],
            ],
            default => throw new \InvalidArgumentException("Unknown test contact key: $key"),
        };
    }

    public static function contactDataProvider(): array
    {
        return [
            'minimal' => ['minimal'],
            'with_email' => ['with_email'],
            'with_phone' => ['with_phone'],
            'with_address' => ['with_address'],
            'with_organization' => ['with_organization'],
            'with_note' => ['with_note'],
            'full' => ['full'],
        ];
    }

    private function buildContactRequest(string $addressBookId, array $data): array
    {
        $contactId = uniqid();
        $r = new ContactSetRequest($this->account->id());
        $p = $r->create($contactId);

        $p->in($addressBookId);

        // name
        $name = $p->name();
        $name->full($data['name']['full']);
        $name->components()->kind('given')->value($data['name']['given']);
        $name->components()->kind('surname')->value($data['name']['surname']);

        // emails
        if ($data['emails'] !== null) {
            foreach ($data['emails'] as $i => $email) {
                $e = $p->emails('email-' . $i);
                $e->address($email['address']);
                $e->context($email['context']);
            }
        }

        // phones
        if ($data['phones'] !== null) {
            foreach ($data['phones'] as $i => $phone) {
                $ph = $p->phones('phone-' . $i);
                $ph->number($phone['number']);
                $ph->context($phone['context']);
            }
        }

        // addresses
        if ($data['addresses'] !== null) {
            foreach ($data['addresses'] as $i => $address) {
                $a = $p->addresses('address-' . $i);
                $a->full($address['full']);
                $a->country($address['country']);
            }
        }

        // organizations
        if ($data['organizations'] !== null) {
            foreach ($data['organizations'] as $i => $org) {
                $o = $p->organizations('org-' . $i);
                $o->name($org['name']);
            }
        }

        // notes
        if ($data['notes'] !== null) {
            foreach ($data['notes'] as $i => $note) {
                $n = $p->notes('note-' . $i);
                $n->contents($note['contents']);
            }
        }

        return [$r, $contactId];
    }

    /**
     * @dataProvider contactDataProvider
     */
    public function testContactSetCreate(string $contactKey): void
    {
        $data = $this->generateContactData($contactKey);
        [$r0, $createId] = $this->buildContactRequest($this->addressBookId, $data);

        // transceive
        $bundle = $this->client->perform([$r0]);

        // verify create response
        $this->assertEquals(1, $bundle->tally());
        $this->assertInstanceOf(ContactSetResponse::class, $bundle->first());
        $result = $bundle->first()->createSuccess($createId);
        $this->assertNotNull($result);
        $this->assertArrayHasKey('id', $result);
    }

    public function testContactSetUpdate(): void
    {
        // construct create request
        $data = $this->generateContactData('minimal');
        [$r0, $createId] = $this->buildContactRequest($this->addressBookId, $data);

        // transceive
        $bundle = $this->client->perform([$r0]);

        // extract contact id
        $result = $bundle->first()->createSuccess($createId);
        $contactId = $result['id'];

        // construct modify request
        $r1 = new ContactSetRequest($this->account->id());
        $p1 = $r1->update($contactId);
        $p1->name()->full('Updated Contact ' . time());

        // transceive
        $bundle = $this->client->perform([$r1]);

        // verify modify response
        $this->assertEquals(1, $bundle->tally());
        $this->assertInstanceOf(ContactSetResponse::class, $bundle->first());
        $result = $bundle->first()->updateSuccess($contactId);
        $this->assertNotNull($result);
        $this->assertArrayHasKey('id', $result);
    }

    public function testContactSetDelete(): void
    {
        // construct create request
        $data = $this->generateContactData('minimal');
        [$r0, $createId] = $this->buildContactRequest($this->addressBookId, $data);

        // transceive
        $bundle = $this->client->perform([$r0]);

        // extract contact id
        $result = $bundle->first()->createSuccess($createId);
        $contactId = $result['id'];

        // construct delete request
        $r1 = new ContactSetRequest($this->account->id());
        $r1->delete($contactId);

        // transceive
        $bundle = $this->client->perform([$r1]);

        // verify delete response
        $this->assertEquals(1, $bundle->tally());
        $this->assertInstanceOf(ContactSetResponse::class, $bundle->first());
        $result = $bundle->first()->deleteSuccess($contactId);
        $this->assertNotNull($result);
        $this->assertArrayHasKey('id', $result);
    }

    public function testContactGetSpecific(): void
    {
        // construct create request
        $data = $this->generateContactData('with_email');
        [$r0, $createId] = $this->buildContactRequest($this->addressBookId, $data);

        // transceive
        $bundle = $this->client->perform([$r0]);

        // extract contact id
        $result = $bundle->first()->createSuccess($createId);
        $contactId = $result['id'];

        // construct fetch request
        $r1 = new ContactGetRequest($this->account->id());
        $r1->target($contactId);

        // transceive
        $bundle = $this->client->perform([$r1]);

        // verify fetch response
        $this->assertEquals(1, $bundle->tally());
        $this->assertInstanceOf(ContactGetResponse::class, $bundle->first());
        $result = $bundle->first()->object(0);
        $this->assertInstanceOf(ContactParameters::class, $result);
        $this->assertEquals($contactId, $result->id());
        $this->assertEquals($data['name']['full'], $result->name()->full());
    }

    public function testContactGetAll(): void
    {
        // construct create request
        $data = $this->generateContactData('minimal');
        [$r0, $createId] = $this->buildContactRequest($this->addressBookId, $data);

        // transceive
        $this->client->perform([$r0]);

        // construct fetch request
        $r1 = new ContactGetRequest($this->account->id());

        // transceive
        $bundle = $this->client->perform([$r1]);

        // verify fetch response
        $this->assertEquals(1, $bundle->tally());
        $this->assertInstanceOf(ContactGetResponse::class, $bundle->first());
        $results = $bundle->first()->objects();
        $this->assertIsArray($results);
        $this->assertNotEmpty($results);
        foreach ($results as $result) {
            $this->assertInstanceOf(ContactParameters::class, $result);
        }
    }

    public function testContactGetWithProperties(): void
    {
        // construct create request
        $data = $this->generateContactData('with_email');
        [$r0, $createId] = $this->buildContactRequest($this->addressBookId, $data);

        // transceive
        $this->client->perform([$r0]);

        // construct fetch request
        $r1 = new ContactGetRequest($this->account->id());
        $r1->property('id', 'name', 'emails');

        // transceive
        $bundle = $this->client->perform([$r1]);

        // verify fetch response
        $this->assertEquals(1, $bundle->tally());
        $this->assertInstanceOf(ContactGetResponse::class, $bundle->first());
        $result = $bundle->first()->object(0);
        $this->assertInstanceOf(ContactParameters::class, $result);
        $this->assertNotEmpty($result->id());
        $this->assertNotNull($result->name());
        $this->assertEmpty($result->phones());
    }

    public function testContactQueryWithoutFilter(): void
    {
        // construct create request
        $data = $this->generateContactData('minimal');
        [$r0, $createId] = $this->buildContactRequest($this->addressBookId, $data);

        // transceive
        $this->client->perform([$r0]);

        // construct query request
        $r1 = new ContactQueryRequest($this->account->id());

        // transceive
        $bundle = $this->client->perform([$r1]);

        // verify query response
        $this->assertEquals(1, $bundle->tally());
        $this->assertInstanceOf(ContactQueryResponse::class, $bundle->first());
        $results = $bundle->first()->list();
        $this->assertIsArray($results);
        $this->assertNotEmpty($results);
    }

    public function testContactQueryWithMatchingFilter(): void
    {
        // construct create request
        $data = $this->generateContactData('minimal');
        [$r0, $createId] = $this->buildContactRequest($this->addressBookId, $data);

        // transceive
        $this->client->perform([$r0]);

        // construct query request
        $r1 = new ContactQueryRequest($this->account->id());
        $r1->filter()->in($this->addressBookId);

        // transceive
        $bundle = $this->client->perform([$r1]);

        // verify query response
        $this->assertEquals(1, $bundle->tally());
        $this->assertInstanceOf(ContactQueryResponse::class, $bundle->first());
        $results = $bundle->first()->list();
        $this->assertIsArray($results);
        $this->assertNotEmpty($results);
    }

    public function testContactQueryWithNonMatchingFilter(): void
    {
        // construct query request
        $r0 = new ContactQueryRequest($this->account->id());
        $r0->filter()->name('This Contact Does Not Exist ' . uniqid());

        // transceive
        $bundle = $this->client->perform([$r0]);

        // verify query response
        $this->assertEquals(1, $bundle->tally());
        $this->assertInstanceOf(ContactQueryResponse::class, $bundle->first());
        $results = $bundle->first()->list();
        $this->assertIsArray($results);
        $this->assertEmpty($results);
    }

    public function testContactQueryWithSorting(): void
    {
        // construct create requests for two contacts with different surnames
        $data1 = $this->generateContactData('minimal');
        [$r0, $id1] = $this->buildContactRequest($this->addressBookId, $data1);

        $data2 = $this->generateContactData('with_email');
        [$r1, $id2] = $this->buildContactRequest($this->addressBookId, $data2);

        // transceive
        $this->client->perform([$r0, $r1]);

        // construct ascending sort query
        $rq0 = new ContactQueryRequest($this->account->id());
        $rq0->filter()->in($this->addressBookId);
        $rq0->sort()->created(true);

        // transceive ascending query
        $bundleAscending = $this->client->perform([$rq0]);

        // verify ascending fetch response
        $this->assertEquals(1, $bundleAscending->tally());
        $this->assertInstanceOf(ContactQueryResponse::class, $bundleAscending->first());
        $ascendingResults = $bundleAscending->first()->list();
        $this->assertIsArray($ascendingResults);
        $this->assertNotEmpty($ascendingResults);

        // construct descending sort query
        $rq1 = new ContactQueryRequest($this->account->id());
        $rq1->filter()->in($this->addressBookId);
        $rq1->sort()->created(false);

        // transceive descending query
        $bundleDescending = $this->client->perform([$rq1]);

        // verify descending fetch response
        $this->assertEquals(1, $bundleDescending->tally());
        $this->assertInstanceOf(ContactQueryResponse::class, $bundleDescending->first());
        $descendingResults = $bundleDescending->first()->list();
        $this->assertIsArray($descendingResults);
        $this->assertNotEmpty($descendingResults);
        $this->assertCount(count($ascendingResults), $descendingResults);

        $firstAscending = $ascendingResults[0];
        $lastDescending = $descendingResults[count($descendingResults) - 1];
        $this->assertSame($firstAscending, $lastDescending);
    }
}
