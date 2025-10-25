<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient\Authentication;

class JsonBasicCookie implements IAuthenticationJsonBasic, IAuthenticationCookie {

    public function __construct (
        private ?string $Id = null,
        private ?string $Secret = null,
        private ?string $Location = null,
        private ?string $CookieAuthenticationLocation = null,
        private mixed $CookieStoreId = null,
        private $CookieRetrieveStore = null,
        private $CookieDepositStore = null,
    ) {}

    public function getId(): ?string {
        return $this->Id;
    }

    public function setId(?string $value): void {
        $this->Id = $value;
    }

    public function getSecret(): ?string {
        return $this->Secret;
    }

    public function setSecret(?string $value): void {
        $this->Secret = $value;
    }

    public function getLocation(): ?string {
        return $this->Location;
    }

    public function setLocation(?string $value): void {
        $this->Location = $value;
    }

    public function getCookieAuthenticationLocation(): ?string {
        return $this->CookieAuthenticationLocation;
    }

    public function setCookieAuthenticationLocation(?string $value): void {
        $this->CookieAuthenticationLocation = $value;
    }

    public function getCookieStoreId(): mixed {
        return $this->CookieStoreId;
    }

    public function setCookieStoreId(mixed $value): void {
        $this->CookieStoreId = $value;
    }

    public function getCookieStoreRetrieve(): ?callable {
        return $this->CookieRetrieveStore;
    }

    public function setCookieStoreRetrieve(?callable $value): void {
        $this->CookieRetrieveStore = $value;
    }

    public function getCookieStoreDeposit(): ?callable {
        return $this->CookieDepositStore;
    }

    public function setCookieStoreDeposit(?callable $value): void {
        $this->CookieDepositStore = $value;
    }
    
}
