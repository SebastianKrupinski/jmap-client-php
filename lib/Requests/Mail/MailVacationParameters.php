<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Requests\Mail;

use DateTimeInterface;
use JmapClient\Requests\RequestParameters;

class MailVacationParameters extends RequestParameters
{
    public function __construct(&$parameters = null)
    {
        parent::__construct($parameters);
    }

    /**
     * Set whether vacation response is enabled
     *
     * @param bool $value Should vacation response be sent
     *
     * @return static
     */
    public function enabled(bool $value): static
    {
        return $this->parameter('isEnabled', $value);
    }

    /**
     * Set the start date for vacation response
     *
     * @param DateTimeInterface $value UTC date-time string (null for immediate effect)
     *
     * @return static
     */
    public function start(DateTimeInterface $value): static
    {
        return $this->parameter('fromDate', $value->format(self::DATE_FORMAT_UTC));
    }

    /**
     * Set the end date for vacation response
     *
     * @param DateTimeInterface $value UTC date-time string (null for indefinite)
     *
     * @return static
     */
    public function end(DateTimeInterface $value): static
    {
        return $this->parameter('toDate', $value->format(self::DATE_FORMAT_UTC));
    }

    /**
     * Set the subject line for vacation response
     *
     * @param string|null $value Subject text (null for server-generated)
     *
     * @return static
     */
    public function subject(string $value): static
    {
        return $this->parameter('subject', $value);
    }

    /**
     * Set the plaintext body for vacation response
     *
     * @param string|null $value Plaintext body content
     *
     * @return static
     */
    public function textBody(string $value): static
    {
        return $this->parameter('textBody', $value);
    }

    /**
     * Set the HTML body for vacation response
     *
     * @param string|null $value HTML body content
     *
     * @return static
     */
    public function htmlBody(string $value): static
    {
        return $this->parameter('htmlBody', $value);
    }
}
