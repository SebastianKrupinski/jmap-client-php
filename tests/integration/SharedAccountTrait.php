<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Tests\Integration;

use JmapClient\Client;
use JmapClient\Responses\ResponseException;
use JmapClient\Session\Account;

/**
 * Helpers for tests that need a second user sharing data with the first user
 */
trait SharedAccountTrait
{
    /**
     * Reconnect as the first user, so the session lists accounts shared since the last connect
     *
     * @return array{0: Client, 1: Account} the reconnected client and the shared account
     */
    private function connectToSharedAccount(string $accountId): array
    {
        $client = ClientFactory::instance('service1');
        $client->connect();
        $account = $client->sessionAccounts()?->account($accountId);
        $this->assertNotNull($account, 'Shared account is not listed in the session of the first user');
        $this->assertFalse($account->personal());
        return [$client, $account];
    }

    /**
     * Failure message describing a method level error response
     */
    private function describeError(mixed $response): string
    {
        return $response instanceof ResponseException ? $response->type() . ': ' . $response->description() : '';
    }
}
