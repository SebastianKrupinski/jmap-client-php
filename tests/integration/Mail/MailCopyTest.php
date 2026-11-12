<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Tests\Integration\Mail;

use JmapClient\Client;
use JmapClient\Requests\Mail\MailboxSet as MailboxSetRequest;
use JmapClient\Requests\Mail\MailCopy as MailCopyRequest;
use JmapClient\Requests\Mail\MailGet as MailGetRequest;
use JmapClient\Requests\Mail\MailSet as MailSetRequest;
use JmapClient\Responses\Mail\MailCopy as MailCopyResponse;
use JmapClient\Session\Account;
use JmapClient\Tests\Integration\ClientFactory;
use JmapClient\Tests\Integration\SharedAccountTrait;
use PHPUnit\Framework\TestCase;

class MailCopyTest extends TestCase
{
    use SharedAccountTrait;

    private Client $owner;
    private Account $ownerAccount;
    private ?string $ownerMailboxId = null;
    private Client $client;
    private Account $account;
    private ?string $mailboxId = null;

    public function setUp(): void
    {
        parent::setUp();
        $this->owner = ClientFactory::instance('service2');
        $this->owner->connect();
        $this->ownerAccount = $this->owner->sessionAccountDefault('mail');
        $this->client = ClientFactory::instance('service1');
        $this->client->connect();
        $this->account = $this->client->sessionAccountDefault('mail');
    }

    public function tearDown(): void
    {
        parent::tearDown();
        if ($this->ownerMailboxId !== null) {
            $r0 = new MailboxSetRequest($this->ownerAccount->id());
            $r0->destroyContents(true);
            $r0->delete($this->ownerMailboxId);
            $this->owner->perform([$r0]);
        }
        if ($this->mailboxId !== null) {
            $r1 = new MailboxSetRequest($this->account->id());
            $r1->destroyContents(true);
            $r1->delete($this->mailboxId);
            $this->client->perform([$r1]);
        }
    }

    public function testMailCopyBetweenAccounts(): void
    {
        // construct create request for a mailbox shared read only with the first user
        $mailboxId = uniqid();
        $emailId = uniqid();
        $subject = 'Test Mail Copy ' . time();
        $r0 = new MailboxSetRequest($this->ownerAccount->id());
        $p0 = $r0->create($mailboxId);
        $p0->label('Test Mail Copy Source ' . time());
        $p0->sharees($this->account->id())->readItems(true);

        // transceive
        $bundle = $this->owner->perform([$r0]);
        $result = $bundle->first()->createSuccess($mailboxId);
        $this->assertNotNull($result, json_encode($bundle->first()->createFailure($mailboxId)));
        $this->ownerMailboxId = $result['id'];

        // construct create request for the email in the shared mailbox
        $r1 = new MailSetRequest($this->ownerAccount->id());
        $p1 = $r1->create($emailId);
        $p1->in($this->ownerMailboxId);
        $p1->from('user2@example1.com', 'User Two');
        $p1->to('user1@example1.com', 'User One');
        $p1->subject($subject);
        $p1->bodyTextPlain('Hello from the second user.', 'body');

        // transceive
        $bundle = $this->owner->perform([$r1]);
        $result = $bundle->first()->createSuccess($emailId);
        $this->assertNotNull($result, json_encode($bundle->first()->createFailure($emailId)));
        $sourceId = $result['id'];

        // reconnect as the first user and create the destination mailbox
        [$this->client, $sharedAccount] = $this->connectToSharedAccount($this->ownerAccount->id());
        $destinationId = uniqid();
        $r2 = new MailboxSetRequest($this->account->id());
        $r2->create($destinationId)->label('Test Mail Copy Destination ' . time());
        $bundle = $this->client->perform([$r2]);
        $this->mailboxId = $bundle->first()->createSuccess($destinationId)['id'];

        // construct copy request from the shared account into the destination mailbox
        $copyId = uniqid();
        $r3 = new MailCopyRequest($this->account->id());
        $r3->sourceAccount($sharedAccount->id());
        $r3->copy($copyId, $sourceId)->in($this->mailboxId)->flagged();

        // transceive
        $bundle = $this->client->perform([$r3]);

        // verify copy response
        $this->assertEquals(1, $bundle->tally());
        $response = $bundle->first();
        $this->assertInstanceOf(MailCopyResponse::class, $response, $this->describeError($response));
        $this->assertEquals($sharedAccount->id(), $response->sourceAccount());
        $result = $response->createSuccess($copyId);
        $this->assertNotNull($result, json_encode($response->createFailure($copyId)));
        $this->assertArrayHasKey('id', $result);

        // verify the copy is in the destination mailbox with the overridden keyword
        $r4 = new MailGetRequest($this->account->id());
        $r4->target($result['id']);
        $r4->property('id', 'subject', 'mailboxIds', 'keywords');
        $bundle = $this->client->perform([$r4]);
        $mail = $bundle->first()->object(0);
        $this->assertEquals($subject, $mail->subject());
        $this->assertContains($this->mailboxId, $mail->in());
        $this->assertTrue($mail->flagged());
    }
}
