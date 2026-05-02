<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Tests\Unit\Requests\Mail;

use JmapClient\Requests\Mail\MailParameters;
use JmapClient\Requests\Mail\MailPart;
use PHPUnit\Framework\TestCase;

class MailParametersTest extends TestCase
{
    private object $parameters;

    private MailParameters $mail;

    protected function setUp(): void
    {
        $parameters = null;

        $this->mail = new MailParameters($parameters);
        $this->mail->bind($parameters);
        $this->parameters = $parameters;
    }

    public function testIn(): void
    {
        $this->mail
            ->in('mailbox-1')
            ->in('mailbox-2');

        $this->assertEquals((object) [
            'mailbox-1' => true,
            'mailbox-2' => true,
        ], $this->parameters->mailboxIds);
    }

    public function testHeaders(): void
    {
        $this->mail->headers([
            'Subject' => 'Example subject',
            'X-Test' => 'Header value',
        ]);

        $this->assertSame('Example subject', $this->parameters->{'header:Subject:asRaw'});
        $this->assertSame('Header value', $this->parameters->{'header:X-Test:asRaw'});
    }

    public function testMessageId(): void
    {
        $this->mail
            ->messageId('<message-1@example.com>')
            ->messageId('<message-2@example.com>');

        $this->assertSame([
            '<message-1@example.com>',
            '<message-2@example.com>',
        ], $this->parameters->messageId);
    }

    public function testInReplyTo(): void
    {
        $this->mail
            ->inReplyTo('<reply-1@example.com>')
            ->inReplyTo('<reply-2@example.com>');

        $this->assertSame([
            '<reply-1@example.com>',
            '<reply-2@example.com>',
        ], $this->parameters->inReplyTo);
    }

    public function testReferences(): void
    {
        $this->mail
            ->references('<ref-1@example.com>')
            ->references('<ref-2@example.com>');

        $this->assertSame([
            '<ref-1@example.com>',
            '<ref-2@example.com>',
        ], $this->parameters->references);
    }

    public function testReceived(): void
    {
        $this->mail->received('2026-05-01T11:22:33Z');

        $this->assertSame('2026-05-01T11:22:33Z', $this->parameters->receivedAt);
    }

    public function testSent(): void
    {
        $this->mail->sent('2026-05-01T10:11:12Z');

        $this->assertSame('2026-05-01T10:11:12Z', $this->parameters->sentAt);
    }

    public function testSender(): void
    {
        $this->mail->sender('sender@example.com', 'Example Sender');

        $this->assertCount(1, $this->parameters->sender);
        $this->assertSame('Example Sender', $this->parameters->sender[0]->name);
        $this->assertSame('sender@example.com', $this->parameters->sender[0]->email);
    }

    public function testFrom(): void
    {
        $this->mail->from('from@example.com', 'From Sender');

        $this->assertCount(1, $this->parameters->from);
        $this->assertSame('From Sender', $this->parameters->from[0]->name);
        $this->assertSame('from@example.com', $this->parameters->from[0]->email);
    }

    public function testTo(): void
    {
        $this->mail->to('to@example.com', 'To Recipient');

        $this->assertCount(1, $this->parameters->to);
        $this->assertSame('To Recipient', $this->parameters->to[0]->name);
        $this->assertSame('to@example.com', $this->parameters->to[0]->email);
    }

    public function testCc(): void
    {
        $this->mail->cc('cc@example.com', 'Copy Recipient');

        $this->assertCount(1, $this->parameters->cc);
        $this->assertSame('Copy Recipient', $this->parameters->cc[0]->name);
        $this->assertSame('cc@example.com', $this->parameters->cc[0]->email);
    }

    public function testBcc(): void
    {
        $this->mail->bcc('bcc@example.com', 'Blind Recipient');

        $this->assertCount(1, $this->parameters->bcc);
        $this->assertSame('Blind Recipient', $this->parameters->bcc[0]->name);
        $this->assertSame('bcc@example.com', $this->parameters->bcc[0]->email);
    }

    public function testReplyTo(): void
    {
        $this->mail->replyTo('reply@example.com', 'Reply Recipient');

        $this->assertCount(1, $this->parameters->replyTo);
        $this->assertSame('Reply Recipient', $this->parameters->replyTo[0]->name);
        $this->assertSame('reply@example.com', $this->parameters->replyTo[0]->email);
    }

    public function testSubject(): void
    {
        $this->mail->subject('Example subject');

        $this->assertSame('Example subject', $this->parameters->subject);
    }

    public function testBodyText(): void
    {
        $this->mail->bodyText('Body content', 'text/plain', 'part-1');

        $this->assertSame('part-1', $this->parameters->bodyStructure->partId);
        $this->assertSame('text/plain', $this->parameters->bodyStructure->type);
        $this->assertSame('Body content', $this->parameters->bodyValues->{'part-1'}->value);
        $this->assertFalse($this->parameters->bodyValues->{'part-1'}->isTruncated);
        $this->assertFalse($this->parameters->bodyValues->{'part-1'}->isEncodingProblem);
    }

    public function testBodyTextPlain(): void
    {
        $this->mail->bodyTextPlain('Plain body', 'plain-part');

        $this->assertSame('plain-part', $this->parameters->bodyStructure->partId);
        $this->assertSame('text/plain', $this->parameters->bodyStructure->type);
        $this->assertSame('Plain body', $this->parameters->bodyValues->{'plain-part'}->value);
    }

    public function testBodyTextHtml(): void
    {
        $this->mail->bodyTextHtml('<p>Hello world</p>', 'html-part');

        $this->assertSame('html-part', $this->parameters->bodyStructure->partId);
        $this->assertSame('text/html', $this->parameters->bodyStructure->type);
        $this->assertSame('<p>Hello world</p>', $this->parameters->bodyValues->{'html-part'}->value);
    }

    public function testBodyPartStructure(): void
    {
        $part = $this->mail->bodyPartStructure();
        $part->id('body-part')->type('multipart/alternative')->header('Content-Language', 'en');

        $this->assertInstanceOf(MailPart::class, $part);
        $this->assertSame('body-part', $this->parameters->bodyStructure->partId);
        $this->assertSame('multipart/alternative', $this->parameters->bodyStructure->type);
        $this->assertSame('Content-Language', $this->parameters->bodyStructure->headers[0]->name);
        $this->assertSame('en', $this->parameters->bodyStructure->headers[0]->value);
    }

    public function testBodyPartValue(): void
    {
        $this->mail->bodyPartValue('part-1', 'Body value', true, true);

        $this->assertSame('Body value', $this->parameters->bodyValues->{'part-1'}->value);
        $this->assertTrue($this->parameters->bodyValues->{'part-1'}->isTruncated);
        $this->assertTrue($this->parameters->bodyValues->{'part-1'}->isEncodingProblem);
    }

    public function testBodyPartValues(): void
    {
        $this->mail->bodyPartValues([
            'part-1' => (object) [
                'value' => 'Body value',
                'isTruncated' => false,
                'isEncodingProblem' => false,
            ],
            'part-2' => (object) [
                'value' => '<p>HTML body</p>',
                'isTruncated' => true,
                'isEncodingProblem' => false,
            ],
        ]);

        $this->assertSame('Body value', $this->parameters->bodyValues->{'part-1'}->value);
        $this->assertTrue($this->parameters->bodyValues->{'part-2'}->isTruncated);
    }

    public function testAttachment(): void
    {
        $attachment = $this->mail->attachment([
            'blobId' => 'attachment-blob',
            'type' => 'application/pdf',
            'name' => 'file.pdf',
            'size' => 1234,
        ]);

        $this->assertInstanceOf(MailPart::class, $attachment);
        $this->assertCount(1, $this->parameters->attachments);
        $this->assertSame('attachment-blob', $this->parameters->attachments[0]->blobId);
        $this->assertSame('application/pdf', $this->parameters->attachments[0]->type);
        $this->assertSame('file.pdf', $this->parameters->attachments[0]->name);
        $this->assertSame(1234, $this->parameters->attachments[0]->size);
    }

    public function testKeywords(): void
    {
        $this->mail->keywords([
            '$draft' => true,
            '$seen' => false,
            '$flagged' => true,
            'custom-label' => true,
        ]);

        $this->assertEquals((object) [
            '$draft' => true,
            '$seen' => false,
            '$flagged' => true,
            'custom-label' => true,
        ], $this->parameters->keywords);
    }

    public function testKeyword(): void
    {
        $this->mail->keyword('custom-label', true);

        $this->assertTrue($this->parameters->keywords->{'custom-label'});
    }

    public function testDraft(): void
    {
        $this->mail->draft();

        $this->assertTrue($this->parameters->keywords->{'$draft'});
    }

    public function testSeen(): void
    {
        $this->mail->seen(false);

        $this->assertFalse($this->parameters->keywords->{'$seen'});
    }

    public function testFlagged(): void
    {
        $this->mail->flagged();

        $this->assertTrue($this->parameters->keywords->{'$flagged'});
    }

    public function testAnswered(): void
    {
        $this->mail->answered();

        $this->assertTrue($this->parameters->keywords->{'$answered'});
    }

    public function testForwarded(): void
    {
        $this->mail->forwarded();

        $this->assertTrue($this->parameters->keywords->{'$forwarded'});
    }

    public function testPhishing(): void
    {
        $this->mail->phishing(false);

        $this->assertFalse($this->parameters->keywords->{'$phishing'});
    }

    public function testJunk(): void
    {
        $this->mail->junk();

        $this->assertTrue($this->parameters->keywords->{'$junk'});
    }

    public function testNotjunk(): void
    {
        $this->mail->notjunk(false);

        $this->assertFalse($this->parameters->keywords->{'$notjunk'});
    }
}
