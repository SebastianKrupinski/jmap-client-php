<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Responses\Mail;

use JmapClient\Responses\ResponseParameters;

class MailIdentityParameters extends ResponseParameters
{
    public function id(): string|null
    {
        return $this->parameter('id');
    }

    public function name(): string|null
    {
        return $this->parameter('name');
    }

    public function address(): string|null
    {
        return $this->parameter('email');
    }

    public function reply(): array|null
    {
        return $this->parameter('replyTo');
    }

    public function bcc(): array|null
    {
        return $this->parameter('bcc');
    }

    public function signature(): string|null
    {
        return !empty($this->parameter('htmlSignature')) ?: $this->parameter('textSignature');
    }

    public function signatureHtml(): string|null
    {
        return $this->parameter('htmlSignature');
    }

    public function signatureText(): string|null
    {
        return $this->parameter('textSignature');
    }

    public function deletable(): bool
    {
        return $this->parameter('mayDelete');
    }

}
