<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Tests\Integration\Mail;

use JmapClient\Client;
use JmapClient\Requests\Mail\MailboxSet as MailboxSetRequest;
use JmapClient\Requests\Mail\MailIdentitySet as MailIdentitySetRequest;
use JmapClient\Requests\Mail\MailSet as MailSetRequest;
use JmapClient\Requests\Mail\MailSubmissionGet as MailSubmissionGetRequest;
use JmapClient\Requests\Mail\MailSubmissionSet as MailSubmissionSetRequest;
use JmapClient\Requests\Mail\MailSubmissionUndoStatus;
use JmapClient\Responses\Mail\MailSubmissionGet as MailSubmissionGetResponse;
use JmapClient\Responses\ResponseException;
use JmapClient\Session\Account;
use JmapClient\Tests\Integration\ClientFactory;
use PHPUnit\Framework\TestCase;

class MailSubmissionTest extends TestCase
{
    private Client $client;
    private Account $account;
    private string $address;
    private ?string $identityId = null;
    private ?string $mailboxId = null;

    public function setUp(): void
    {
        parent::setUp();
        $this->client = ClientFactory::instance('service1');
        $this->client->connect();
        $this->account = $this->client->sessionAccountDefault('submission');
        $this->address = ClientFactory::identifier('service1');
    }

    public function tearDown(): void
    {
        parent::tearDown();
        if ($this->mailboxId !== null) {
            $r0 = new MailboxSetRequest($this->account->id());
            $r0->destroyContents(true);
            $r0->delete($this->mailboxId);
            $this->client->perform([$r0]);
        }
        if ($this->identityId !== null) {
            $r1 = new MailIdentitySetRequest($this->account->id());
            $r1->delete($this->identityId);
            $this->client->perform([$r1]);
        }
    }

    public function testMailSubmissionReadsSubmissionProperties(): void
    {
        // construct create requests for the sending identity and a mailbox to hold the message
        $identityId = uniqid();
        $mailboxId = uniqid();
        $r0 = new MailIdentitySetRequest($this->account->id());
        $p0 = $r0->create($identityId);
        $p0->name('Test Submission Identity ' . time());
        $p0->address($this->address);
        $r1 = new MailboxSetRequest($this->account->id());
        $r1->create($mailboxId)->label('Test Mail Submission ' . time());

        // transceive
        $bundle = $this->client->perform([$r0, $r1]);
        $result = $bundle->response(0)->createSuccess($identityId);
        $this->assertNotNull($result, json_encode($bundle->response(0)->createFailure($identityId)));
        $this->identityId = $result['id'];
        $result = $bundle->response(1)->createSuccess($mailboxId);
        $this->assertNotNull($result, json_encode($bundle->response(1)->createFailure($mailboxId)));
        $this->mailboxId = $result['id'];

        // construct create request for the message, sent to the same user so delivery stays local
        $emailId = uniqid();
        $r2 = new MailSetRequest($this->account->id());
        $p2 = $r2->create($emailId);
        $p2->in($this->mailboxId);
        $p2->from($this->address, 'User One');
        $p2->to($this->address, 'User One');
        $p2->subject('Test Mail Submission ' . time());
        $p2->bodyTextPlain('Mail submission test body.', 'body');

        // transceive
        $bundle = $this->client->perform([$r2]);
        $result = $bundle->first()->createSuccess($emailId);
        $this->assertNotNull($result, json_encode($bundle->first()->createFailure($emailId)));
        $emailId = $result['id'];

        // construct submission request with an explicit envelope
        $submissionId = uniqid();
        $r3 = new MailSubmissionSetRequest($this->account->id());
        $p3 = $r3->create($submissionId);
        $p3->identity($this->identityId);
        $p3->message($emailId);
        $p3->from($this->address);
        $p3->to([$this->address]);

        // transceive
        $bundle = $this->client->perform([$r3]);
        $response = $bundle->first();
        $this->assertNotInstanceOf(ResponseException::class, $response, $response instanceof ResponseException ? $response->type() . ': ' . $response->description() : '');
        $result = $response->createSuccess($submissionId);
        $this->assertNotNull($result, json_encode($response->createFailure($submissionId)));

        // fetch the submission
        $r4 = new MailSubmissionGetRequest($this->account->id());
        $r4->target($result['id']);
        $bundle = $this->client->perform([$r4]);
        $this->assertInstanceOf(MailSubmissionGetResponse::class, $bundle->first());
        $submission = $bundle->first()->object(0);

        // verify the submission properties
        $this->assertSame($this->identityId, $submission->identity());
        $this->assertSame($emailId, $submission->message());
        $this->assertNotNull($submission->thread());
        $this->assertSame($this->address, $submission->from());
        $this->assertSame([$this->address], $submission->to());
        $this->assertNotNull($submission->sent());
        $this->assertInstanceOf(MailSubmissionUndoStatus::class, $submission->undoStatus());
        $this->assertIsArray($submission->dsnBlobs());
        $this->assertIsArray($submission->mdnBlobs());
    }
}
