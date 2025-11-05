<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Requests\Contacts;

use JmapClient\Requests\RequestParameters;

class ContactComponentParameters extends RequestParameters
{
    public function kind(string $value): static
    {
        $this->parameter('kind', $value);
        return $this;
    }

    public function value(string $value): static
    {
        $this->parameter('value', $value);
        return $this;
    }

}
