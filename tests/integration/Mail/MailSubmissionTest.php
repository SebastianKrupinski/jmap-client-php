<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Tests\Integration\Mail;

use JmapClient\Client;
use JmapClient\Enums\Mail\MailSubmissionUndoStatus;
use JmapClient\Requests\Mail\MailboxSet as MailboxSetRequest;
use JmapClient\Requests\Mail\MailIdentitySet as MailIdentitySetRequest;
use JmapClient\Requests\Mail\MailSet as MailSetRequest;
use JmapClient\Requests\Mail\MailSubmissionGet as MailSubmissionGetRequest;
use JmapClient\Requests\Mail\MailSubmissionQuery as MailSubmissionQueryRequest;
use JmapClient\Requests\Mail\MailSubmissionSet as MailSubmissionSetRequest;
use JmapClient\Responses\Mail\MailSubmissionGet as MailSubmissionGetResponse;
use JmapClient\Responses\Mail\MailSubmissionParameters;
use JmapClient\Responses\Mail\MailSubmissionQuery as MailSubmissionQueryResponse;
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

    /**
     * Create the sending identity, a mailbox and a message to the same user so delivery stays local
     *
     * @return string the message id
     */
    private function createMessage(): string
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

        // construct create request for the message
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
        return $result['id'];
    }

    /**
     * Submit the message and return the submission id
     *
     * @param array<string,string|null> $parameters SMTP MAIL FROM parameters
     */
    private function submit(string $emailId, array $parameters = []): string
    {
        $submissionId = uniqid();
        $r0 = new MailSubmissionSetRequest($this->account->id());
        $p0 = $r0->create($submissionId);
        $p0->identity($this->identityId);
        $p0->message($emailId);
        $p0->from($this->address, $parameters);
        $p0->to([$this->address]);

        $bundle = $this->client->perform([$r0]);
        $response = $bundle->first();
        $this->assertNotInstanceOf(ResponseException::class, $response, $response instanceof ResponseException ? $response->type() . ': ' . $response->description() : '');
        $result = $response->createSuccess($submissionId);
        $this->assertNotNull($result, json_encode($response->createFailure($submissionId)));
        return $result['id'];
    }

    private function fetch(string $submissionId): MailSubmissionParameters
    {
        $r0 = new MailSubmissionGetRequest($this->account->id());
        $r0->target($submissionId);
        $bundle = $this->client->perform([$r0]);
        $this->assertInstanceOf(MailSubmissionGetResponse::class, $bundle->first());
        return $bundle->first()->object(0);
    }

    public function testMailSubmissionReadsSubmissionProperties(): void
    {
        $emailId = $this->createMessage();
        $submission = $this->fetch($this->submit($emailId));

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

    public function testMailSubmissionCancel(): void
    {
        // hold the message in the queue for an hour, so it can still be canceled
        $submissionId = $this->submit($this->createMessage(), ['HOLDFOR' => '3600']);
        $this->assertSame(MailSubmissionUndoStatus::Pending, $this->fetch($submissionId)->undoStatus());

        // construct cancel request
        $r0 = new MailSubmissionSetRequest($this->account->id());
        $r0->update($submissionId)->cancel();

        // transceive
        $bundle = $this->client->perform([$r0]);
        $response = $bundle->first();
        $this->assertNotNull($response->updateSuccess($submissionId), json_encode($response->updateFailure($submissionId)));

        // verify the submission is canceled
        $this->assertSame(MailSubmissionUndoStatus::Canceled, $this->fetch($submissionId)->undoStatus());
    }

    private function query(callable $filter): array
    {
        $r0 = new MailSubmissionQueryRequest($this->account->id());
        $filter($r0->filter());
        $bundle = $this->client->perform([$r0]);
        $response = $bundle->first();
        $this->assertInstanceOf(
            MailSubmissionQueryResponse::class,
            $response,
            $response instanceof ResponseException ? $response->type() . ': ' . $response->description() : ''
        );
        return $response->list();
    }

    public function testMailSubmissionQueryFilters(): void
    {
        $emailId = $this->createMessage();
        $submissionId = $this->submit($emailId, ['HOLDFOR' => '3600']);
        $threadId = $this->fetch($submissionId)->thread();

        // each id filter finds the submission
        $this->assertContains($submissionId, $this->query(fn ($f) => $f->messages($emailId)));
        $this->assertContains($submissionId, $this->query(fn ($f) => $f->identities($this->identityId)));
        $this->assertContains($submissionId, $this->query(fn ($f) => $f->threads($threadId)));
        $this->assertContains($submissionId, $this->query(fn ($f) => $f->messages($emailId)->undoStatus(MailSubmissionUndoStatus::Pending)));

        // and filters that do not match leave it out
        $this->assertNotContains($submissionId, $this->query(fn ($f) => $f->messages($emailId)->undoStatus(MailSubmissionUndoStatus::Canceled)));
        $this->assertNotContains($submissionId, $this->query(fn ($f) => $f->messages('nonexistent')));

        // cancel the held message so it is not delivered after the test
        $r0 = new MailSubmissionSetRequest($this->account->id());
        $r0->update($submissionId)->cancel();
        $this->client->perform([$r0]);
    }
}
