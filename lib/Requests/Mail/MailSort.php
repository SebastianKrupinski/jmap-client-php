<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Requests\Mail;

use JmapClient\Enums\Mail\MailKeyword;
use JmapClient\Requests\RequestSort;

class MailSort extends RequestSort
{
    public function received(bool $value = false): static
    {
        $this->condition('receivedAt', $value);
        return $this;
    }

    public function sent(bool $value = false): static
    {
        $this->condition('sentAt', $value);
        return $this;
    }

    public function from(bool $value = false): static
    {
        $this->condition('from', $value);
        return $this;
    }

    public function to(bool $value = false): static
    {
        $this->condition('to', $value);
        return $this;
    }

    public function subject(bool $value = false): static
    {
        $this->condition('subject', $value);
        return $this;
    }

    public function size(bool $value = false): static
    {
        $this->condition('size', $value);
        return $this;
    }

    public function keyword(MailKeyword|string $keyword, bool $value = false): static
    {
        $this->condition('hasKeyword', $value, $keyword instanceof MailKeyword ? $keyword->value : $keyword);
        return $this;
    }

    public function threadKeywordAll(MailKeyword|string $keyword, bool $value = false): static
    {
        $this->condition('allInThreadHaveKeyword', $value, $keyword instanceof MailKeyword ? $keyword->value : $keyword);
        return $this;
    }

    public function threadKeywordSome(MailKeyword|string $keyword, bool $value = false): static
    {
        $this->condition('someInThreadHaveKeyword', $value, $keyword instanceof MailKeyword ? $keyword->value : $keyword);
        return $this;
    }
}
