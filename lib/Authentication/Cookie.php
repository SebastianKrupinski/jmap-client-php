<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Authentication;

class Cookie implements IAuthenticationCookie
{
    public function __construct(
        private ?string $CookieAuthenticationLocation = null,
        private ?string $CookieStoreId = null,
        private $CookieRetrieveStore = null,
        private $CookieDepositStore = null,
    ) {
    }

    public function getCookieAuthenticationLocation(): ?string
    {
        return $this->CookieAuthenticationLocation;
    }

    public function setCookieAuthenticationLocation(?string $value): void
    {
        $this->CookieAuthenticationLocation = $value;
    }

    public function getCookieStoreId(): mixed
    {
        return $this->CookieStoreId;
    }

    public function setCookieStoreId(mixed $value): void
    {
        $this->CookieStoreId = $value;
    }

    public function getCookieStoreRetrieve(): ?callable
    {
        return $this->CookieRetrieveStore;
    }

    public function setCookieStoreRetrieve(?callable $value): void
    {
        $this->CookieRetrieveStore = $value;
    }

    public function getCookieStoreDeposit(): ?callable
    {
        return $this->CookieDepositStore;
    }

    public function setCookieStoreDeposit(?callable $value): void
    {
        $this->CookieDepositStore = $value;
    }

}
