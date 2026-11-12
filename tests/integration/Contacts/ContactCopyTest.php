<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Tests\Integration\Contacts;

use JmapClient\Client;
use JmapClient\Requests\Contacts\AddressBookSet as AddressBookSetRequest;
use JmapClient\Requests\Contacts\ContactCopy as ContactCopyRequest;
use JmapClient\Requests\Contacts\ContactGet as ContactGetRequest;
use JmapClient\Requests\Contacts\ContactSet as ContactSetRequest;
use JmapClient\Responses\Contacts\ContactCopy as ContactCopyResponse;
use JmapClient\Session\Account;
use JmapClient\Tests\Integration\ClientFactory;
use JmapClient\Tests\Integration\SharedAccountTrait;
use PHPUnit\Framework\TestCase;

class ContactCopyTest extends TestCase
{
    use SharedAccountTrait;

    private Client $owner;
    private Account $ownerAccount;
    private ?string $ownerAddressBookId = null;
    private Client $client;
    private Account $account;
    private ?string $addressBookId = null;

    public function setUp(): void
    {
        parent::setUp();
        $this->owner = ClientFactory::instance('service2');
        $this->owner->connect();
        $this->ownerAccount = $this->owner->sessionAccountDefault('contacts');
        $this->client = ClientFactory::instance('service1');
        $this->client->connect();
        $this->account = $this->client->sessionAccountDefault('contacts');
    }

    public function tearDown(): void
    {
        parent::tearDown();
        if ($this->ownerAddressBookId !== null) {
            $r0 = new AddressBookSetRequest($this->ownerAccount->id());
            $r0->deleteContents(true);
            $r0->delete($this->ownerAddressBookId);
            $this->owner->perform([$r0]);
        }
        if ($this->addressBookId !== null) {
            $r1 = new AddressBookSetRequest($this->account->id());
            $r1->deleteContents(true);
            $r1->delete($this->addressBookId);
            $this->client->perform([$r1]);
        }
    }

    public function testContactCopyBetweenAccounts(): void
    {
        // construct create request for an address book shared read only with the first user
        $bookId = uniqid();
        $r0 = new AddressBookSetRequest($this->ownerAccount->id());
        $p0 = $r0->create($bookId);
        $p0->label('Test Contact Copy Source ' . time());
        $p0->sharees($this->account->id())->read(true);

        // transceive
        $bundle = $this->owner->perform([$r0]);
        $result = $bundle->first()->createSuccess($bookId);
        $this->assertNotNull($result, json_encode($bundle->first()->createFailure($bookId)));
        $this->ownerAddressBookId = $result['id'];

        // construct create request for the contact in the shared address book
        $contactId = uniqid();
        $fullName = 'Copy Contact ' . time();
        $r1 = new ContactSetRequest($this->ownerAccount->id());
        $p1 = $r1->create($contactId);
        $p1->in($this->ownerAddressBookId);
        $p1->name()->full($fullName);

        // transceive
        $bundle = $this->owner->perform([$r1]);
        $result = $bundle->first()->createSuccess($contactId);
        $this->assertNotNull($result, json_encode($bundle->first()->createFailure($contactId)));
        $sourceId = $result['id'];

        // reconnect as the first user and create the destination address book
        [$this->client, $sharedAccount] = $this->connectToSharedAccount($this->ownerAccount->id());
        $destinationId = uniqid();
        $r2 = new AddressBookSetRequest($this->account->id());
        $r2->create($destinationId)->label('Test Contact Copy Destination ' . time());
        $bundle = $this->client->perform([$r2]);
        $this->addressBookId = $bundle->first()->createSuccess($destinationId)['id'];

        // construct copy request from the shared account into the destination address book
        $copyId = uniqid();
        $r3 = new ContactCopyRequest($this->account->id());
        $r3->sourceAccount($sharedAccount->id());
        $r3->copy($copyId, $sourceId)->in($this->addressBookId);

        // transceive
        $bundle = $this->client->perform([$r3]);

        // verify copy response
        $this->assertEquals(1, $bundle->tally());
        $response = $bundle->first();
        $this->assertInstanceOf(ContactCopyResponse::class, $response, $this->describeError($response));
        $this->assertEquals($sharedAccount->id(), $response->sourceAccount());
        $result = $response->createSuccess($copyId);
        $this->assertNotNull($result, json_encode($response->createFailure($copyId)));
        $this->assertArrayHasKey('id', $result);

        // verify the copy is in the destination address book
        $r4 = new ContactGetRequest($this->account->id());
        $r4->target($result['id']);
        $bundle = $this->client->perform([$r4]);
        $contact = $bundle->first()->object(0);
        $this->assertEquals($fullName, $contact->name()?->full());
        $this->assertContains($this->addressBookId, (array) $contact->in());
    }
}
