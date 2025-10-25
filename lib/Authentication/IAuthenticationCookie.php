<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient\Authentication;

interface IAuthenticationCookie extends IAuthentication {

    public function getCookieAuthenticationLocation(): ?string;

    public function setCookieAuthenticationLocation(string $value): void;

    public function getCookieStoreId(): mixed;

    public function setCookieStoreId(mixed $value): void;

    public function getCookieStoreRetrieve(): ?callable;

    public function setCookieStoreRetrieve(callable $value): void;

    public function getCookieStoreDeposit(): ?callable;

    public function setCookieStoreDeposit(callable $value): void;

}
