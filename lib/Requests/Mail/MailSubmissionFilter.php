<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Requests\Mail;

use DateTimeInterface;
use JmapClient\Requests\RequestFilter;

class MailSubmissionFilter extends RequestFilter
{
    public function identities(string ...$value): static
    {
        $this->condition('identityIds', $value);
        return $this;
    }

    public function messages(string ...$value): static
    {
        $this->condition('emailIds', $value);
        return $this;
    }

    public function threads(string ...$value): static
    {
        $this->condition('threadIds', $value);
        return $this;
    }

    public function undoStatus(MailSubmissionUndoStatus $value): static
    {
        $this->condition('undoStatus', $value->value);
        return $this;
    }

    public function sendBefore(DateTimeInterface $value): static
    {
        $this->condition('before', $this->utcDate($value));
        return $this;
    }

    public function sendAfter(DateTimeInterface $value): static
    {
        $this->condition('after', $this->utcDate($value));
        return $this;
    }
}
