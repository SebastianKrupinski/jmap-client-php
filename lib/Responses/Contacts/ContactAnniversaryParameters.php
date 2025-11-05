<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Responses\Contacts;

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
        if ($date->{'@type'} === 'Timestamp') {
            return new ContactDateStampParameters($date);
        }
        if ($date->{'@type'} === 'PartialDate') {
            return new ContactDatePartialParameters($date);
        }

        return null;
    }

}
