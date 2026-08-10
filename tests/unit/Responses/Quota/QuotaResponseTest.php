<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Tests\Unit\Responses\Quota;

use JmapClient\Responses\Quota\QuotaChanges;
use JmapClient\Responses\Quota\QuotaGet;
use JmapClient\Responses\Quota\QuotaParameters;
use JmapClient\Responses\Quota\QuotaQuery;
use JmapClient\Responses\Quota\QuotaQueryChanges;
use JmapClient\Responses\ResponseBundle;
use PHPUnit\Framework\TestCase;

class QuotaResponseTest extends TestCase
{
    public function testGetDeserializesQuotaObject(): void
    {
        $bundle = new ResponseBundle([
            'methodResponses' => [[
                'Quota/get',
                [
                    'accountId' => 'account-1',
                    'state' => 'state-1',
                    'list' => [[
                        'id' => 'quota-1',
                        'resourceType' => 'octets',
                        'used' => 1056,
                        'warnLimit' => 1600,
                        'softLimit' => 1800,
                        'hardLimit' => 2000,
                        'scope' => 'account',
                        'name' => 'Storage',
                        'types' => ['Email'],
                        'description' => 'Personal account storage.',
                    ]],
                    'notFound' => [],
                ],
                'get-1',
            ]],
            'sessionState' => 'session-1',
        ]);

        $response = $bundle->first();
        $quota = $response->object(0);

        $this->assertInstanceOf(QuotaGet::class, $response);
        $this->assertInstanceOf(QuotaParameters::class, $quota);
        $this->assertSame('quota-1', $quota->id());
        $this->assertSame('octets', $quota->resource());
        $this->assertSame(1056, $quota->used());
        $this->assertSame(1600, $quota->warnLimit());
        $this->assertSame(1800, $quota->softLimit());
        $this->assertSame(2000, $quota->hardLimit());
        $this->assertSame('account', $quota->scope());
        $this->assertSame('Storage', $quota->name());
        $this->assertSame(['Email'], $quota->types());
        $this->assertSame('Personal account storage.', $quota->description());
    }

    public function testChangesExposesUpdatedProperties(): void
    {
        $response = new QuotaChanges([
            'Quota/changes',
            [
                'accountId' => 'account-1',
                'oldState' => 'state-1',
                'newState' => 'state-2',
                'hasMoreChanges' => false,
                'updatedProperties' => ['used'],
                'created' => [],
                'updated' => ['quota-1'],
                'destroyed' => [],
            ],
            'changes-1',
        ]);

        $this->assertSame(['used'], $response->updatedProperties());
    }

    public function testChangesPreservesNullUpdatedProperties(): void
    {
        $response = new QuotaChanges([
            'Quota/changes',
            ['accountId' => 'account-1', 'updatedProperties' => null],
            'changes-1',
        ]);

        $this->assertNull($response->updatedProperties());
    }

    public function testQueryResponsesAreRegistered(): void
    {
        $bundle = new ResponseBundle([
            'methodResponses' => [
                ['Quota/query', ['accountId' => 'account-1', 'queryState' => '1', 'ids' => []], '1'],
                ['Quota/queryChanges', ['accountId' => 'account-1', 'oldQueryState' => '1', 'newQueryState' => '2'], '2'],
            ],
        ]);

        $this->assertInstanceOf(QuotaQuery::class, $bundle->response(0));
        $this->assertInstanceOf(QuotaQueryChanges::class, $bundle->response(1));
    }
}
