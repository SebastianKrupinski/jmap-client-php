<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Requests\Sieve;

use JmapClient\Requests\RequestParameters;

class ScriptParameters extends RequestParameters
{
    private const PARAMETER_LIST = ['name', 'blobId'];

    public function parametersRaw(array $value): static
    {
        $this->_parameters = (object) array_intersect_key($value, array_flip(self::PARAMETER_LIST));
        return $this;
    }

    public function label(string|null $value): static
    {
        $this->parameter('name', $value);
        return $this;
    }

    public function blob(string $value): static
    {
        $this->parameter('blobId', $value);
        return $this;
    }
}
