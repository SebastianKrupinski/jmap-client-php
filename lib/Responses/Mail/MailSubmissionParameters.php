<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Responses\Mail;

use JmapClient\Requests\Mail\MailSubmissionUndoStatus;
use JmapClient\Responses\ResponseParameters;

class MailSubmissionParameters extends ResponseParameters
{
    public function id(): string|null
    {
        return $this->parameter('id');
    }

    public function identity(): string|null
    {
        return $this->parameter('identityId');
    }

    public function message(): string|null
    {
        return $this->parameter('emailId');
    }

    public function thread(): string|null
    {
        return $this->parameter('threadId');
    }

    public function envelope(): array|null
    {
        return $this->parameter('envelope');
    }

    public function from(): string|null
    {
        return $this->parameter('envelope')['mailFrom']['email'] ?? null;
    }

    public function to(): array|null
    {
        $recipients = $this->parameter('envelope')['rcptTo'] ?? null;
        if (!is_array($recipients)) {
            return null;
        }
        return array_values(array_filter(array_map(fn ($recipient) => $recipient['email'] ?? null, $recipients), 'is_string'));
    }

    public function sent(): string|null
    {
        return $this->parameter('sendAt');
    }

    public function undoStatus(): MailSubmissionUndoStatus|null
    {
        $value = $this->parameter('undoStatus');
        return is_string($value) ? MailSubmissionUndoStatus::tryFrom($value) : null;
    }

    public function deliveryStatus(): array|null
    {
        return $this->parameter('deliveryStatus');
    }

    public function delivery(string $recipient): array|null
    {
        return $this->parameter('deliveryStatus')[$recipient] ?? null;
    }

    public function dsnBlobs(): array
    {
        return $this->parameter('dsnBlobIds') ?? [];
    }

    public function mdnBlobs(): array
    {
        return $this->parameter('mdnBlobIds') ?? [];
    }
}
