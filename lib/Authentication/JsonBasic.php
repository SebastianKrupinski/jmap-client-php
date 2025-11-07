<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Authentication;

class JsonBasic implements IAuthenticationJsonBasic
{
    public function __construct(
        public ?string $Id = null,
        public ?string $Secret = null,
        public ?string $Location = null,
    ) {
    }

    public function getId(): ?string
    {
        return $this->Id;
    }

    public function setId(?string $value): void
    {
        $this->Id = $value;
    }

    public function getSecret(): ?string
    {
        return $this->Secret;
    }

    public function setSecret(?string $value): void
    {
        $this->Secret = $value;
    }

    public function getLocation(): ?string
    {
        return $this->Location;
    }

    public function setLocation(?string $value): void
    {
        $this->Location = $value;
    }
}
