<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient\Authentication;

class Bearer implements IAuthenticationBearer
{
    public function __construct (
        private ?string $Id = null,
        private ?string $Token = null,
        private int $Expiry = 0,
        private ?string $Location = null
    ) {}

    public function getId(): ?string {
        return $this->Id;
    }

    public function setId(?string $value): void {
        $this->Id = $value;
    }

    public function getToken(): ?string {
        return $this->Token;
    }

    public function setToken(?string $value): void {
        $this->Token = $value;
    }

    public function getExpiry(): ?int {
        return $this->Expiry;
    }

    public function setExpiry(?int $value): void {
        $this->Expiry = $value;
    }
    
    public function getLocation(): ?string {
        return $this->Location;
    }

    public function setLocation(?string $value): void {
        $this->Location = $value;
    }

}
