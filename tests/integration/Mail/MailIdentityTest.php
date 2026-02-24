<?php

declare(strict_types=1);
/**
 * SPDX-FileCopyrightText: Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Tests\Integration\Mail;

use JmapClient\Client;
use JmapClient\Requests\Mail\MailIdentityGet as MailIdentityGetRequest;
use JmapClient\Requests\Mail\MailIdentitySet as MailIdentitySetRequest;
use JmapClient\Responses\Mail\MailIdentityGet as MailIdentityGetResponse;
use JmapClient\Responses\Mail\MailIdentityParameters;
use JmapClient\Responses\Mail\MailIdentitySet as MailIdentitySetResponse;
use JmapClient\Session\Account;
use JmapClient\Tests\Integration\ClientFactory;
use PHPUnit\Framework\TestCase;

class MailIdentityTest extends TestCase
{
    private Client $client;
    private Account $account;
    private string $identityEmail = '';

    public function setUp(): void
    {
        parent::setUp();
        $this->client = ClientFactory::instance('service1');
        $this->client->connect();
        $this->account = $this->client->sessionAccountDefault('submission');

        // derive the email address from the service config identifier
        $this->identityEmail = ClientFactory::identifier('service1');

        // create a seed identity so get/set tests always have at least one identity to work with
        $seedId = uniqid();
        $rSeed = new MailIdentitySetRequest($this->account->id());
        $pSeed = $rSeed->create($seedId);
        $pSeed->name('Test Identity ' . time());
        $pSeed->address($this->identityEmail);
        $response = $this->client->perform([$rSeed]);
        $result = $response->first()->createSuccess($seedId);
        $this->assertNotNull($result, 'Failed to create seed identity');
    }

    public function tearDown(): void
    {
        parent::tearDown();
        // construct list request
        $r0 = new MailIdentityGetRequest($this->account->id());
        // transceive
        $bundle = $this->client->perform([$r0]);
        $response = $bundle->first();
        if (!($response instanceof MailIdentityGetResponse)) {
            return;
        }
        $commands = [];
        // construct deletion requests for test-created identities
        foreach ($response->objects() as $identity) {
            if ($identity->deletable() &&
                (str_starts_with($identity->name(), 'Test Identity ') ||
                 str_starts_with($identity->name(), 'Modified Identity ') ||
                 str_starts_with($identity->name(), 'To Delete Identity '))) {
                $rq = new MailIdentitySetRequest($this->account->id());
                $rq->delete($identity->id());
                $commands[] = $rq;
            }
        }
        // transceive
        if (!empty($commands)) {
            //$this->client->perform($commands);
        }
    }

    public function testMailIdentityGetAll(): void
    {
        // construct fetch request
        $r0 = new MailIdentityGetRequest($this->account->id());

        // transceive
        $bundle = $this->client->perform([$r0]);

        // verify fetch response — per RFC 8621 §6 servers MUST provide at least one Identity
        $this->assertEquals(1, $bundle->tally());
        $this->assertInstanceOf(MailIdentityGetResponse::class, $bundle->first());
        $results = $bundle->first()->objects();
        $this->assertIsArray($results);
        $this->assertNotEmpty($results);
        foreach ($results as $result) {
            $this->assertInstanceOf(MailIdentityParameters::class, $result);
        }
    }

    public function testMailIdentityGetSpecific(): void
    {
        // retrieve all identities to find a real id
        $r0 = new MailIdentityGetRequest($this->account->id());
        $bundle = $this->client->perform([$r0]);
        $first = $bundle->first()->object(0);
        $this->assertInstanceOf(MailIdentityParameters::class, $first);
        $identityId = $first->id();

        // construct fetch request for a specific identity
        $r1 = new MailIdentityGetRequest($this->account->id());
        $r1->target($identityId);

        // transceive
        $bundle = $this->client->perform([$r1]);

        // verify fetch response
        $this->assertEquals(1, $bundle->tally());
        $this->assertInstanceOf(MailIdentityGetResponse::class, $bundle->first());
        $result = $bundle->first()->object(0);
        $this->assertInstanceOf(MailIdentityParameters::class, $result);
        $this->assertEquals($identityId, $result->id());
        $this->assertNotEmpty($result->name());
    }

    public function testMailIdentityGetWithProperties(): void
    {
        // construct fetch request with limited properties
        $r0 = new MailIdentityGetRequest($this->account->id());
        $r0->property('id', 'name');

        // transceive
        $bundle = $this->client->perform([$r0]);

        // verify fetch response — unrequested properties should be absent/null
        $this->assertEquals(1, $bundle->tally());
        $this->assertInstanceOf(MailIdentityGetResponse::class, $bundle->first());
        $result = $bundle->first()->object(0);
        $this->assertInstanceOf(MailIdentityParameters::class, $result);
        $this->assertNotEmpty($result->id());
        $this->assertNotNull($result->name());
        $this->assertNull($result->address());
    }

    public function testMailIdentitySetCreate(): void
    {
        // construct create request
        $createId = uniqid();
        $r0 = new MailIdentitySetRequest($this->account->id());
        $p0 = $r0->create($createId);
        $p0->name('Test Identity ' . time());
        $p0->address($this->identityEmail);
        $p0->signaturePlain('-- Test signature');

        // transceive
        $bundle = $this->client->perform([$r0]);

        // verify create response
        $this->assertEquals(1, $bundle->tally());
        $this->assertInstanceOf(MailIdentitySetResponse::class, $bundle->first());
        $result = $bundle->first()->createSuccess($createId);
        $this->assertNotNull($result);
        $this->assertArrayHasKey('id', $result);
    }

    public function testMailIdentitySetUpdate(): void
    {
        // construct create request
        $createId = uniqid();
        $r0 = new MailIdentitySetRequest($this->account->id());
        $p0 = $r0->create($createId);
        $p0->name('Test Identity ' . time());
        $p0->address($this->identityEmail);

        // transceive
        $bundle = $this->client->perform([$r0]);

        // extract identity id
        $result = $bundle->first()->createSuccess($createId);
        $identityId = $result['id'];

        // construct modify request
        $r1 = new MailIdentitySetRequest($this->account->id());
        $p1 = $r1->update($identityId);
        $p1->name('Modified Identity ' . time());
        $p1->signaturePlain('-- Modified test signature');

        // transceive
        $bundle = $this->client->perform([$r1]);

        // verify modify response
        $this->assertEquals(1, $bundle->tally());
        $this->assertInstanceOf(MailIdentitySetResponse::class, $bundle->first());
        $result = $bundle->first()->updateSuccess($identityId);
        $this->assertNotNull($result);
        $this->assertArrayHasKey('id', $result);
    }

    public function testMailIdentitySetDelete(): void
    {
        // construct create request
        $createId = uniqid();
        $r0 = new MailIdentitySetRequest($this->account->id());
        $p0 = $r0->create($createId);
        $p0->name('To Delete Identity ' . time());
        $p0->address($this->identityEmail);

        // transceive
        $bundle = $this->client->perform([$r0]);

        // extract identity id
        $result = $bundle->first()->createSuccess($createId);
        $identityId = $result['id'];

        // verify the newly created identity is deletable
        $r1 = new MailIdentityGetRequest($this->account->id());
        $r1->target($identityId);
        $bundle = $this->client->perform([$r1]);
        $identity = $bundle->first()->object(0);
        $this->assertInstanceOf(MailIdentityParameters::class, $identity);
        $this->assertTrue($identity->deletable(), 'Newly created identity should be deletable');

        // construct delete request
        $r2 = new MailIdentitySetRequest($this->account->id());
        $r2->delete($identityId);

        // transceive
        $bundle = $this->client->perform([$r2]);

        // verify delete response
        $this->assertEquals(1, $bundle->tally());
        $this->assertInstanceOf(MailIdentitySetResponse::class, $bundle->first());
        $result = $bundle->first()->deleteSuccess($identityId);
        $this->assertNotNull($result);
        $this->assertArrayHasKey('id', $result);
    }
}
