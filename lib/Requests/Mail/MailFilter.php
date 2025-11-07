<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Requests\Mail;

use DateTime;
use DateTimeImmutable;
use JmapClient\Requests\RequestFilter;

class MailFilter extends RequestFilter
{
    public function in(string $value): static
    {
        $this->condition('inMailbox', $value);
        return $this;
    }

    public function inOmit(array $value): static
    {
        $this->condition('inMailboxOtherThan', $value);
        return $this;
    }

    public function text(string $value): static
    {
        $this->condition('text', $value);
        return $this;
    }

    public function from(string $value): static
    {
        $this->condition('from', $value);
        return $this;
    }

    public function to(string $value): static
    {
        $this->condition('to', $value);
        return $this;
    }

    public function cc(string $value): static
    {
        $this->condition('cc', $value);
        return $this;
    }

    public function bcc(string $value): static
    {
        $this->condition('bcc', $value);
        return $this;
    }

    public function subject(string $value): static
    {
        $this->condition('subject', $value);
        return $this;
    }

    public function body(string $value): static
    {
        $this->condition('body', $value);
        return $this;
    }

    public function hasAttachment(bool $value): static
    {
        $this->condition('hasAttachment', $value);
        return $this;
    }

    public function keywordPresent(string $value): static
    {
        $this->condition('before', $value);
        return $this;
    }

    public function keywordAbsent(string $value): static
    {
        $this->condition('after', $value);
        return $this;
    }

    public function receivedBefore(DateTime|DateTimeImmutable $value): static
    {
        $this->condition('before', $value->format(self::DATE_FORMAT_UTC));
        return $this;
    }

    public function receivedAfter(DateTime|DateTimeImmutable $value): static
    {
        $this->condition('after', $value->format(self::DATE_FORMAT_UTC));
        return $this;
    }

    public function sizeMin(int $value): static
    {
        $this->condition('minSize', $value);
        return $this;
    }

    public function sizeMax(int $value): static
    {
        $this->condition('maxSize', $value);
        return $this;
    }
}
