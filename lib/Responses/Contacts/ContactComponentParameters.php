<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Responses\Contacts;

use JmapClient\Responses\ResponseParameters;

class ContactComponentParameters extends ResponseParameters
{
    public function kind(): string|null
    {
        return $this->parameter('kind');
    }

    public function value(): string|null
    {
        return $this->parameter('value');
    }
}
