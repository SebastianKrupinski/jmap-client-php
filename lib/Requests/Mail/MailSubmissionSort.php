<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Requests\Mail;

use JmapClient\Requests\RequestSort;

class MailSubmissionSort extends RequestSort
{
    public function sent(bool $value = false): static
    {
        $this->condition('sentAt', $value);
        return $this;
    }

    public function messageId(bool $value = false): static
    {
        $this->condition('emailId', $value);
        return $this;
    }

    public function threadId(bool $value = false): static
    {
        $this->condition('threadId', $value);
        return $this;
    }

}
