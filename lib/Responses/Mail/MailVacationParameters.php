<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Responses\Mail;

use DateTimeImmutable;
use JmapClient\Responses\ResponseParameters;

class MailVacationParameters extends ResponseParameters
{
    /**
     * Get the vacation response identifier
     *
     * @return string|null
     */
    public function id(): string|null
    {
        return $this->parameter('id');
    }

    /**
     * Check if vacation response is enabled
     *
     * @return bool|null
     */
    public function enabled(): bool
    {
        return $this->parameter('isEnabled') ?? false;
    }

    /**
     * Get the start date for vacation response
     *
     * @return DateTimeImmutable|null
     */
    public function start(): DateTimeImmutable|null
    {
        $value = $this->parameter('fromDate');
        return ($value) ? new DateTimeImmutable($value) : null;
    }

    /**
     * Get the end date for vacation response
     *
     * @return string|null UTC date-time string or null
     */
    public function end(): ?DateTimeImmutable
    {
        $value = $this->parameter('toDate');
        return ($value) ? new DateTimeImmutable($value) : null;
    }

    /**
     * Get the subject line for vacation response
     *
     * @return string|null
     */
    public function subject(): ?string
    {
        return $this->parameter('subject');
    }

    public function body(): ?string
    {
        return $this->bodyText() ?? $this->bodyHtml() ?? null;
    }

    /**
     * Get the plaintext body for vacation response
     *
     * @return string|null
     */
    public function bodyText(): ?string
    {
        return $this->parameter('textBody');
    }

    /**
     * Get the HTML body for vacation response
     *
     * @return string|null
     */
    public function bodyHtml(): ?string
    {
        return $this->parameter('htmlBody');
    }
}
