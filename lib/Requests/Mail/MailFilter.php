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

class MailFilter extends RequestFilter {
    
    public function in(string $value): self {
        $this->condition('inMailbox', $value);
        return $this;
    }

    public function inOmit(array $value): self {
        $this->condition('inMailboxOtherThan', $value);
        return $this;
    }

    public function text(string $value): self {
        $this->condition('text', $value);
        return $this;
    }

    public function from(string $value): self {
        $this->condition('from', $value);
        return $this;
    }

    public function to(string $value): self {
        $this->condition('to', $value);
        return $this;
    }

    public function cc(string $value): self {
        $this->condition('cc', $value);
        return $this;
    }

    public function bcc(string $value): self {
        $this->condition('bcc', $value);
        return $this;
    }

    public function subject(string $value): self {
        $this->condition('subject', $value);
        return $this;
    }

    public function body(string $value): self {
        $this->condition('body', $value);
        return $this;
    }

    public function hasAttachment(bool $value): self {
        $this->condition('hasAttachment', $value);
        return $this;
    }

    public function keywordPresent(string $value): self {
        $this->condition('before', $value);
        return $this;
    }

    public function keywordAbsent(string $value): self {
        $this->condition('after', $value);
        return $this;
    }

    public function receivedBefore(DateTime|DateTimeImmutable $value): self {
        $this->condition('before', $value->format(self::DATE_FORMAT_UTC));
        return $this;
    }

    public function receivedAfter(DateTime|DateTimeImmutable $value): self {
        $this->condition('after', $value->format(self::DATE_FORMAT_UTC));
        return $this;
    }

    public function sizeMin(int $value): self {
        $this->condition('minSize', $value);
        return $this;
    }

    public function sizeMax(int $value): self {
        $this->condition('maxSize', $value);
        return $this;
    }

}
