<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Tests\Unit\Requests\Quota;

use JmapClient\Requests\Quota\QuotaChanges;
use JmapClient\Requests\Quota\QuotaGet;
use JmapClient\Requests\Quota\QuotaQuery;
use JmapClient\Requests\Quota\QuotaQueryChanges;
use JmapClient\Requests\RequestBundle;
use PHPUnit\Framework\TestCase;

class QuotaRequestTest extends TestCase
{
    public function testGetAllRequestUsesQuotaCapabilityAndOmitsIds(): void
    {
        $request = new QuotaGet('account-1', 'get-1');
        $request->property('id', 'used', 'hardLimit');

        $this->assertSame([
            'using' => [
                'urn:ietf:params:jmap:core',
                'urn:ietf:params:jmap:quota',
            ],
            'methodCalls' => [[
                'Quota/get',
                [
                    'accountId' => 'account-1',
                    'properties' => ['id', 'used', 'hardLimit'],
                ],
                'get-1',
            ]],
        ], json_decode((new RequestBundle($request))->jsonEncode(), true, 512, JSON_THROW_ON_ERROR));
    }

    public function testChangesCanDriveGetIdsAndProperties(): void
    {
        $changes = new QuotaChanges('account-1', 'changes-1');
        $changes->state('state-1')->limitRelative(20);

        $get = new QuotaGet('account-1', 'get-1');
        $get
            ->targetFromRequest($changes, '/updated')
            ->propertyFromRequest($changes, '/updatedProperties');

        $calls = json_decode((new RequestBundle($changes, $get))->jsonEncode(), true, 512, JSON_THROW_ON_ERROR)['methodCalls'];

        $this->assertSame([
            'accountId' => 'account-1',
            'sinceState' => 'state-1',
            'maxChanges' => 20,
        ], $calls[0][1]);
        $this->assertSame([
            'resultOf' => 'changes-1',
            'name' => 'Quota/changes',
            'path' => '/updated',
        ], $calls[1][1]['#ids']);
        $this->assertSame([
            'resultOf' => 'changes-1',
            'name' => 'Quota/changes',
            'path' => '/updatedProperties',
        ], $calls[1][1]['#properties']);
    }

    public function testQuerySupportsEveryRfcFilterAndSort(): void
    {
        $request = new QuotaQuery('account-1', 'query-1');
        $request->filter()
            ->name('example')
            ->scope('account')
            ->resource('octets')
            ->type('Email');
        $request->sort()->name()->used(false);

        $arguments = json_decode($request->jsonEncode(), true, 512, JSON_THROW_ON_ERROR)[1];

        $this->assertSame([
            'name' => 'example',
            'scope' => 'account',
            'resourceType' => 'octets',
            'type' => 'Email',
        ], $arguments['filter']);
        $this->assertSame([
            ['property' => 'name', 'isAscending' => true],
            ['property' => 'used', 'isAscending' => false],
        ], $arguments['sort']);
    }

    public function testQueryChangesUsesQuotaQueryTypes(): void
    {
        $request = new QuotaQueryChanges('account-1', 'query-changes-1');
        $request->filter()->scope('domain');
        $request->sort()->used();
        $request->state('query-state-1')->limitRelative(10)->limitAbsolute('quota-1')->tally(true);

        $data = json_decode($request->jsonEncode(), true, 512, JSON_THROW_ON_ERROR);

        $this->assertSame('Quota/queryChanges', $data[0]);
        $this->assertSame('domain', $data[1]['filter']['scope']);
        $this->assertSame('used', $data[1]['sort'][0]['property']);
        $this->assertSame('query-state-1', $data[1]['sinceQueryState']);
    }
}
