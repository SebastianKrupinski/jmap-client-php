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

class MailSubmissionFilter extends RequestFilter
{
    public function undoStatus(string $value): static
    {
        $this->condition('undoStatus', $value);
        return $this;
    }

    public function sendBefore(DateTime|DateTimeImmutable $value): static
    {
        $this->condition('before', $value->format(self::DATE_FORMAT_LOCAL));
        return $this;
    }

    public function sendAfter(DateTime|DateTimeImmutable $value): static
    {
        $this->condition('after', $value->format(self::DATE_FORMAT_LOCAL));
        return $this;
    }
}
