<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Tests\Unit\Responses\Mail;

use JmapClient\Responses\Mail\MailPart;
use JmapClient\Responses\Mail\MailParameters;
use PHPUnit\Framework\TestCase;

class MailParametersTest extends TestCase
{
    private array $response;

    private MailParameters $mail;

    protected function setUp(): void
    {
        $this->response = [
            'mailboxIds' => [
                'mailbox-1' => true,
                'mailbox-2' => true,
            ],
            'id' => 'mail-1',
            'header:Subject:asRaw' => 'Example subject',
            'header:Received:asText' => 'Fri, 1 May 2026 11:22:33 +0000',
            'header:X-Test:asRaw:all' => ['first value', 'second value'],
            'threadId' => 'thread-1',
            'blobId' => 'blob-1',
            'messageId' => ['<message@example.com>'],
            'references' => ['<ref-1@example.com>', '<ref-2@example.com>'],
            'inReplyTo' => ['<reply@example.com>'],
            'receivedAt' => '2026-05-01T11:22:33Z',
            'sentAt' => '2026-05-01T10:11:12Z',
            'size' => 2048,
            'sender' => [['name' => 'Example Sender', 'email' => 'sender@example.com']],
            'from' => [['name' => 'From Sender', 'email' => 'from@example.com']],
            'to' => [['name' => 'To Recipient', 'email' => 'to@example.com']],
            'cc' => [['name' => 'Copy Recipient', 'email' => 'cc@example.com']],
            'bcc' => [['name' => 'Blind Recipient', 'email' => 'bcc@example.com']],
            'replyTo' => [['name' => 'Reply Recipient', 'email' => 'reply@example.com']],
            'subject' => 'Example subject',
            'preview' => 'Preview text',
            'hasAttachment' => true,
            'bodyValues' => [
                'part-1.1' => [
                    'value' => 'Hello world',
                    'isEncodingProblem' => false,
                    'isTruncated' => false,
                ],
                'part-1.2' => [
                    'value' => '<p>Hello world</p>',
                    'isEncodingProblem' => false,
                    'isTruncated' => false,
                ],
            ],
            'htmlBody' => [
                [
                    'partId' => 'part-1.2',
                    'type' => 'text/html',
                    'size' => 256,
                    'headers' => [
                        ['name' => 'Content-Type', 'value' => 'text/html; charset=utf-8'],
                    ],
                    'language' => ['en'],
                ],
            ],
            'textBody' => [
                [
                    'partId' => 'part-1.1',
                    'type' => 'text/plain',
                    'size' => 128,
                ],
            ],
            'bodyStructure' => [
                'partId' => 'part-1',
                'blobId' => 'blob-part-1',
                'type' => 'multipart/alternative',
                'size' => 1024,
                'subParts' => [
                    [
                        'partId' => 'part-1.1',
                        'type' => 'text/plain',
                        'size' => 128,
                    ],
                    [
                        'partId' => 'part-1.2',
                        'type' => 'text/html',
                        'size' => 256,
                    ],
                ],
            ],
            'attachments' => [
                [
                    'blobId' => 'attachment-1',
                    'name' => 'file.txt',
                    'type' => 'text/plain',
                    'size' => 99,
                    'headers' => [
                        ['name' => 'Content-Disposition', 'value' => 'attachment'],
                    ],
                    'language' => ['en'],
                ],
            ],
            'keywords' => [
                '$draft' => true,
                '$seen' => false,
                '$flagged' => true,
                '$answered' => true,
                '$forwarded' => true,
                '$phishing' => false,
                '$junk' => true,
                '$notjunk' => false,
                'custom-label' => true,
            ],
        ];

        $this->mail = new MailParameters($this->response);
    }

    public function testIn(): void
    {
        $this->assertSame(array_keys($this->response['mailboxIds']), $this->mail->in());
    }

    public function testId(): void
    {
        $this->assertSame($this->response['id'], $this->mail->id());
    }

    public function testThread(): void
    {
        $this->assertSame($this->response['threadId'], $this->mail->thread());
    }

    public function testBlob(): void
    {
        $this->assertSame($this->response['blobId'], $this->mail->blob());
    }

    public function testSize(): void
    {
        $this->assertSame($this->response['size'], $this->mail->size());
    }

    public function testHeader(): void
    {
        $this->assertSame($this->response['header:Subject:asRaw'], $this->mail->header('Subject'));
    }

    public function testHeaderSupportsCustomForm(): void
    {
        $this->assertSame($this->response['header:Received:asText'], $this->mail->header('Received', 'asText'));
    }

    public function testHeaderSupportsAllValues(): void
    {
        $this->assertSame($this->response['header:X-Test:asRaw:all'], $this->mail->header('X-Test', 'asRaw', true));
    }

    public function testMessageId(): void
    {
        $this->assertSame($this->response['messageId'], $this->mail->messageId());
    }

    public function testInReplyTo(): void
    {
        $this->assertSame($this->response['inReplyTo'], $this->mail->inReplyTo());
    }

    public function testReferences(): void
    {
        $this->assertSame($this->response['references'], $this->mail->references());
    }

    public function testReceived(): void
    {
        $this->assertSame($this->response['receivedAt'], $this->mail->received());
    }

    public function testSent(): void
    {
        $this->assertSame($this->response['sentAt'], $this->mail->sent());
    }

    public function testSender(): void
    {
        $this->assertSame($this->response['sender'], $this->mail->sender());
    }

    public function testSenderDefaultsToEmptyArray(): void
    {
        $emptyMail = new MailParameters();

        $this->assertSame([], $emptyMail->sender());
    }

    public function testFrom(): void
    {
        $this->assertSame($this->response['from'], $this->mail->from());
    }

    public function testTo(): void
    {
        $this->assertSame($this->response['to'], $this->mail->to());
    }

    public function testCc(): void
    {
        $this->assertSame($this->response['cc'], $this->mail->cc());
    }

    public function testBcc(): void
    {
        $this->assertSame($this->response['bcc'], $this->mail->bcc());
    }

    public function testReplyTo(): void
    {
        $this->assertSame($this->response['replyTo'], $this->mail->replyTo());
    }

    public function testSubject(): void
    {
        $this->assertSame($this->response['subject'], $this->mail->subject());
    }

    public function testBodyTextWithHtml(): void
    {
        $this->assertSame($this->response['bodyValues']['part-1.2']['value'], $this->mail->bodyText());
    }

    public function testBodyTextFallbackToPlainText(): void
    {
        $response = $this->response;
        unset($response['htmlBody'], $response['bodyValues']['part-1.2']);
        $response['bodyStructure']['subParts'] = [
            [
                'partId' => 'part-1.1',
                'type' => 'text/plain',
                'size' => 128,
            ],
        ];

        $mail = new MailParameters($response);

        $this->assertSame($response['bodyValues']['part-1.1']['value'], $mail->bodyText());
    }

        public function testBodyTextWalksStructureWhenBodyListsAreMissing(): void
    {
        $response = $this->response;
        unset($response['htmlBody'], $response['textBody']);

        $mail = new MailParameters($response);

        $this->assertSame($response['bodyValues']['part-1.2']['value'], $mail->bodyText());
        $this->assertSame($response['bodyValues']['part-1.2']['value'], $mail->bodyTextHtml());
        $this->assertSame($response['bodyValues']['part-1.1']['value'], $mail->bodyTextPlain());
    }

    public function testBodyTextReturnsNullWhenStructureOrBodyValuesAreMissing(): void
    {
        $mail = new MailParameters();

        $this->assertNull($mail->bodyText());
        $this->assertNull($mail->bodyTextHtml());
        $this->assertNull($mail->bodyText());
        $this->assertNull($mail->bodyTextPreview());
    }

    public function testBodyTextHtml(): void
    {
        $this->assertSame($this->response['bodyValues']['part-1.2']['value'], $this->mail->bodyTextHtml());
    }

    public function testBodyTextPlain(): void
    {
        $this->assertSame($this->response['bodyValues']['part-1.1']['value'], $this->mail->bodyTextPlain());
    }

    public function testBodyTextPreview(): void
    {
        $this->assertSame($this->response['preview'], $this->mail->bodyTextPreview());
    }

    public function testBodyPartStructure(): void
    {
        $structure = $this->mail->bodyPartStructure();

        $this->assertInstanceOf(MailPart::class, $structure);
        $this->assertSame($this->response['bodyStructure']['partId'], $structure->id());
        $this->assertSame($this->response['bodyStructure']['type'], $structure->type());
        $this->assertCount(2, $structure->parts());
        $this->assertSame('part-1.1', $structure->parts()[0]->id());
        $this->assertSame('text/html', $structure->parts()[1]->type());
    }

    public function testBodyPartStructureDefaultsToNull(): void
    {
        $mail = new MailParameters();

        $this->assertNull($mail->bodyPartStructure());
    }

    public function testBodyPartValue(): void
    {
        $this->assertSame($this->response['bodyValues']['part-1.1']['value'], $this->mail->bodyPartValue('part-1.1'));
    }

    public function testBodyPartValueReturnsNullForUnknownPart(): void
    {
        $this->assertNull($this->mail->bodyPartValue('missing-part'));
    }

    public function testBodyPartValues(): void
    {
        $this->assertSame($this->response['bodyValues'], $this->mail->bodyPartValues());
    }

    public function testAttachments(): void
    {
        $parts = $this->mail->attachments();

        $this->assertCount(1, $parts);
        $this->assertContainsOnlyInstancesOf(MailPart::class, $parts);
        $this->assertSame('file.txt', $parts[0]->name());
        $this->assertSame($this->response['attachments'][0]['headers'], $parts[0]->headers());
        $this->assertSame($this->response['attachments'][0]['language'], $parts[0]->language());
    }

    public function testAttachmentsDefaultToEmptyArray(): void
    {
        $mail = new MailParameters();

        $this->assertSame([], $mail->attachments());
    }

    public function testHasAttachment(): void
    {
        $this->assertTrue($this->mail->hasAttachment());
    }

    public function testKeywords(): void
    {
        $this->assertSame($this->response['keywords'], $this->mail->keywords());
    }

    public function testKeyword(): void
    {
        $this->assertTrue($this->mail->keyword('custom-label'));
    }

    public function testDraft(): void
    {
        $this->assertTrue($this->mail->draft());
    }

    public function testSeen(): void
    {
        $this->assertFalse($this->mail->seen());
    }

    public function testFlagged(): void
    {
        $this->assertTrue($this->mail->flagged());
    }

    public function testAnswered(): void
    {
        $this->assertTrue($this->mail->answered());
    }

    public function testForwarded(): void
    {
        $this->assertTrue($this->mail->forwarded());
    }

    public function testPhishing(): void
    {
        $this->assertFalse($this->mail->phishing());
    }

    public function testJunk(): void
    {
        $this->assertTrue($this->mail->junk());
    }

    public function testNotJunk(): void
    {
        $this->assertFalse($this->mail->notjunk());
    }

    public function testMissingKeyword(): void
    {
        $this->assertNull($this->mail->keyword('missing-label'));
    }
}