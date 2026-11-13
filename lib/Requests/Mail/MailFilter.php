<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Requests\Mail;

use DateTimeInterface;
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
        $this->condition('hasKeyword', $value);
        return $this;
    }

    public function keywordAbsent(string $value): static
    {
        $this->condition('notKeyword', $value);
        return $this;
    }

    public function threadKeywordAll(string $value): static
    {
        $this->condition('allInThreadHaveKeyword', $value);
        return $this;
    }

    public function threadKeywordSome(string $value): static
    {
        $this->condition('someInThreadHaveKeyword', $value);
        return $this;
    }

    public function threadKeywordNone(string $value): static
    {
        $this->condition('noneInThreadHaveKeyword', $value);
        return $this;
    }

    /**
     * Emails that have the header, and when a value is given, whose header value contains it
     */
    public function header(string $name, ?string $value = null): static
    {
        $this->condition('header', $value === null ? [$name] : [$name, $value]);
        return $this;
    }

    public function receivedBefore(DateTimeInterface $value): static
    {
        $this->condition('before', $this->utcDate($value));
        return $this;
    }

    public function receivedAfter(DateTimeInterface $value): static
    {
        $this->condition('after', $this->utcDate($value));
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
