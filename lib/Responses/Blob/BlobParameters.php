<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Responses\Blob;

use JmapClient\Responses\ResponseParameters;

class BlobParameters extends ResponseParameters
{
    public function id(): string|null
    {
        return $this->parameter('id');
    }

    public function type(): string|null
    {
        return $this->parameter('type');
    }

    public function size(): int|null
    {
        return $this->parameter('size');
    }

    public function dataPlain(): string|null
    {
        return $this->parameter('data:asText');
    }

    public function dataEncoded(): string|null
    {
        return $this->parameter('data:asBase64');
    }
}
