<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Authentication;

class Custom implements IAuthenticationCustom
{
    public function __construct(
        private $Authenticate = null,
    ) {
    }

    public function authenticate(): ?callable
    {
        return $this->Authenticate;
    }
}
