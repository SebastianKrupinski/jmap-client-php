<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Responses\Contacts;

use DateTimeImmutable;
use JmapClient\Responses\ResponseParameters;

class ContactDateStampParameters extends ResponseParameters
{
    public function type(): string|null
    {
        return $this->parameter('@type');
    }

    public function value(): DateTimeImmutable|null
    {
        $date = $this->parameter('value');
        if ($date === null) {
            return new DateTimeImmutable($date);
        }
        return null;
    }

}
