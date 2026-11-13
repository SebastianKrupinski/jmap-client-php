<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Responses\Contacts;

use JmapClient\Enums\Contacts\ContactAnniversaryKind;
use JmapClient\Responses\ResponseParameters;

class ContactAnniversaryParameters extends ResponseParameters
{
    public function type(): string|null
    {
        return $this->parameter('@type');
    }

    public function date(): ContactDateStampParameters|ContactDatePartialParameters|null
    {
        $date = $this->parameter('date');
        if ($date === null) {
            return $date;
        }
        // PartialDate is the default type, a Timestamp must declare its type
        if (($date['@type'] ?? null) === 'Timestamp') {
            return new ContactDateStampParameters($date);
        }
        return new ContactDatePartialParameters($date);
    }

    public function kind(): ContactAnniversaryKind|string|null
    {
        $value = $this->parameter('kind');
        return is_string($value) ? (ContactAnniversaryKind::tryFrom($value) ?? $value) : null;
    }

    public function place(): ContactAddressParameters|null
    {
        $place = $this->parameter('place');
        return $place !== null ? new ContactAddressParameters($place) : null;
    }
}
