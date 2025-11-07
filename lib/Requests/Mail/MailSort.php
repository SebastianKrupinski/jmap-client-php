<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Requests\Mail;

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

    public function keyword(bool $value = false): static
    {
        $this->condition('hasKeyword', $value);
        return $this;
    }
}
