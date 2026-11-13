<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Tests\Unit\Responses\Mail;

use JmapClient\Enums\Mail\MailSubmissionUndoStatus;
use JmapClient\Responses\Mail\MailSubmissionParameters;
use PHPUnit\Framework\TestCase;

class MailSubmissionParametersTest extends TestCase
{
    public function testReadsAllSubmissionProperties(): void
    {
        $submission = new MailSubmissionParameters([
            'id' => 'submission-1',
            'identityId' => 'identity-1',
            'emailId' => 'email-1',
            'threadId' => 'thread-1',
            'envelope' => [
                'mailFrom' => ['email' => 'sender@example.com', 'parameters' => null],
                'rcptTo' => [
                    ['email' => 'one@example.com', 'parameters' => null],
                    ['email' => 'two@example.com', 'parameters' => ['NOTIFY' => 'SUCCESS']],
                ],
            ],
            'sendAt' => '2026-11-12T10:00:00Z',
            'undoStatus' => 'final',
            'deliveryStatus' => [
                'one@example.com' => ['smtpReply' => '250 2.0.0 OK', 'delivered' => 'yes', 'displayed' => 'unknown'],
            ],
            'dsnBlobIds' => ['blob-dsn'],
            'mdnBlobIds' => ['blob-mdn'],
        ]);

        $this->assertSame('submission-1', $submission->id());
        $this->assertSame('identity-1', $submission->identity());
        $this->assertSame('email-1', $submission->message());
        $this->assertSame('thread-1', $submission->thread());
        $this->assertSame('sender@example.com', $submission->from());
        $this->assertSame(['one@example.com', 'two@example.com'], $submission->to());
        $this->assertSame(['NOTIFY' => 'SUCCESS'], $submission->envelope()['rcptTo'][1]['parameters']);
        $this->assertSame('2026-11-12T10:00:00+00:00', $submission->sent()->format(DATE_ATOM));
        $this->assertSame(MailSubmissionUndoStatus::Final, $submission->undoStatus());
        $this->assertSame('yes', $submission->delivery('one@example.com')['delivered']);
        $this->assertNull($submission->delivery('two@example.com'));
        $this->assertSame(['one@example.com'], array_keys($submission->deliveryStatus()));
        $this->assertSame(['blob-dsn'], $submission->dsnBlobs());
        $this->assertSame(['blob-mdn'], $submission->mdnBlobs());
    }

    public function testMissingPropertiesReturnEmptyValues(): void
    {
        $submission = new MailSubmissionParameters(['id' => 'submission-1', 'envelope' => null]);

        $this->assertNull($submission->envelope());
        $this->assertNull($submission->from());
        $this->assertNull($submission->to());
        $this->assertNull($submission->sent());
        $this->assertNull($submission->undoStatus());
        $this->assertNull($submission->deliveryStatus());
        $this->assertNull($submission->delivery('one@example.com'));
        $this->assertSame([], $submission->dsnBlobs());
        $this->assertSame([], $submission->mdnBlobs());
    }

    public function testUnknownUndoStatusReturnsNull(): void
    {
        $submission = new MailSubmissionParameters(['undoStatus' => 'deferred']);

        $this->assertNull($submission->undoStatus());
    }
}
