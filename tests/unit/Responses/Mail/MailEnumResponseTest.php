<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Tests\Unit\Responses\Mail;

use JmapClient\Enums\Mail\MailboxRole;
use JmapClient\Enums\Mail\MailDeliveryStatus;
use JmapClient\Enums\Mail\MailDisplayStatus;
use JmapClient\Enums\Mail\MailHeaderForm;
use JmapClient\Enums\Mail\MailKeyword;
use JmapClient\Responses\Mail\MailboxParameters;
use JmapClient\Responses\Mail\MailParameters;
use JmapClient\Responses\Mail\MailSubmissionParameters;
use PHPUnit\Framework\TestCase;

class MailEnumResponseTest extends TestCase
{
    public function testMailboxRoleReturnsEnumOrCustomString(): void
    {
        $this->assertSame(MailboxRole::Sent, (new MailboxParameters(['role' => 'sent']))->role());
        $this->assertSame('x-custom', (new MailboxParameters(['role' => 'x-custom']))->role());
        $this->assertNull((new MailboxParameters(['role' => null]))->role());
    }

    public function testKeywordAndHeaderAcceptEnums(): void
    {
        $mail = new MailParameters([
            'keywords' => ['$important' => true, 'custom-label' => true],
            'header:Subject:asText' => 'Hello',
        ]);

        $this->assertTrue($mail->keyword(MailKeyword::Important));
        $this->assertTrue($mail->keyword('custom-label'));
        $this->assertNull($mail->keyword(MailKeyword::Phishing));
        $this->assertSame('Hello', $mail->header('Subject', MailHeaderForm::Text));
    }

    public function testDeliveryAndDisplayStatus(): void
    {
        $submission = new MailSubmissionParameters([
            'deliveryStatus' => [
                'one@example.com' => ['smtpReply' => '250 OK', 'delivered' => 'yes', 'displayed' => 'yes'],
                'two@example.com' => ['smtpReply' => '', 'delivered' => 'queued', 'displayed' => 'unknown'],
                'three@example.com' => ['delivered' => 'deferred'],
            ],
        ]);

        $this->assertSame(MailDeliveryStatus::Yes, $submission->delivered('one@example.com'));
        $this->assertSame(MailDisplayStatus::Yes, $submission->displayed('one@example.com'));
        $this->assertSame(MailDeliveryStatus::Queued, $submission->delivered('two@example.com'));
        $this->assertSame(MailDisplayStatus::Unknown, $submission->displayed('two@example.com'));
        $this->assertNull($submission->delivered('three@example.com'));
        $this->assertNull($submission->displayed('three@example.com'));
        $this->assertNull($submission->delivered('missing@example.com'));
    }
}
