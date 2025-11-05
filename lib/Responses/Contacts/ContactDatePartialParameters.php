<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Responses\Contacts;

use JmapClient\Responses\ResponseParameters;

class ContactDatePartialParameters extends ResponseParameters
{
    public function type(): string|null
    {
        return $this->parameter('@type');
    }

    public function year(): int|null
    {
        return $this->parameter('year');
    }

    public function month(): int|null
    {
        return $this->parameter('month');
    }

    public function day(): int|null
    {
        return $this->parameter('day');
    }

    public function scale(): string|null
    {
        return $this->parameter('calendarScale');
    }

}
