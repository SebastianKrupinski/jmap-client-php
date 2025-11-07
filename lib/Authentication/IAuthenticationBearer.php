<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Authentication;

interface IAuthenticationBearer extends IAuthentication
{
    public function getId(): ?string;

    public function setId(?string $value): void;

    public function getToken(): ?string;

    public function setToken(?string $value): void;

    public function getExpiry(): ?int;

    public function setExpiry(?int $value): void;

    public function getLocation(): ?string;

    public function setLocation(string $value): void;
}
