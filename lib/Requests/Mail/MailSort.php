<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient\Requests\Mail;

use JmapClient\Requests\RequestSort;

class MailSort extends RequestSort {

    public function received(bool $value = false): self {
        $this->condition('receivedAt', $value);
        return $this;
    }

    public function sent(bool $value = false): self {
        $this->condition('sentAt', $value);
        return $this;
    }

    public function from(bool $value = false): self {
        $this->condition('from', $value);
        return $this;
    }

    public function to(bool $value = false): self {
        $this->condition('to', $value);
        return $this;
    }

    public function subject(bool $value = false): self {
        $this->condition('subject', $value);
        return $this;
    }

    public function size(bool $value = false): self {
        $this->condition('size', $value);
        return $this;
    }

    public function keyword(bool $value = false): self {
        $this->condition('hasKeyword', $value);
        return $this;
    }

}
