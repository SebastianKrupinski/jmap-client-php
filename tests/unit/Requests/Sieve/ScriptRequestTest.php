<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Tests\Unit\Requests\Sieve;

use JmapClient\Requests\RequestBundle;
use JmapClient\Requests\Sieve\ScriptGet;
use JmapClient\Requests\Sieve\ScriptQuery;
use JmapClient\Requests\Sieve\ScriptSet;
use JmapClient\Requests\Sieve\ScriptValidate;
use PHPUnit\Framework\TestCase;

class ScriptRequestTest extends TestCase
{
    private function decode(...$requests): array
    {
        return json_decode((new RequestBundle(...$requests))->jsonEncode(), true, 512, JSON_THROW_ON_ERROR);
    }

    public function testGetUsesSieveCapability(): void
    {
        $request = new ScriptGet('account-1', 'get-1');
        $request->target('script-1');

        $this->assertSame([
            'using' => ['urn:ietf:params:jmap:core', 'urn:ietf:params:jmap:sieve'],
            'methodCalls' => [['SieveScript/get', ['accountId' => 'account-1', 'ids' => ['script-1']], 'get-1']],
        ], $this->decode($request));
    }

    public function testSetCreatesAndActivatesScript(): void
    {
        $request = new ScriptSet('account-1', 'set-1');
        $request->create('c1')->label('Filters')->blob('blob-1');
        $request->activate('#c1');

        $this->assertSame([
            'accountId' => 'account-1',
            'create' => ['c1' => ['name' => 'Filters', 'blobId' => 'blob-1']],
            'onSuccessActivateScript' => '#c1',
        ], $this->decode($request)['methodCalls'][0][1]);
    }

    public function testSetDeactivatesAndDeletes(): void
    {
        $request = new ScriptSet('account-1', 'set-1');
        $request->deactivate();
        $request->delete('script-1');

        $this->assertSame([
            'accountId' => 'account-1',
            'onSuccessDeactivateScript' => true,
            'destroy' => ['script-1'],
        ], $this->decode($request)['methodCalls'][0][1]);
    }

    public function testQueryFilterAndSort(): void
    {
        $request = new ScriptQuery('account-1', 'query-1');
        $request->filter()->label('Filter')->active(true);
        $request->sort()->active(false)->label(true);

        $command = $this->decode($request)['methodCalls'][0][1];

        $this->assertSame(['name' => 'Filter', 'isActive' => true], $command['filter']);
        $this->assertSame([
            ['property' => 'isActive', 'isAscending' => false],
            ['property' => 'name', 'isAscending' => true],
        ], $command['sort']);
    }

    public function testValidateSendsBlob(): void
    {
        $request = new ScriptValidate('account-1', 'validate-1');
        $request->blob('blob-1');

        $this->assertSame(
            ['SieveScript/validate', ['accountId' => 'account-1', 'blobId' => 'blob-1'], 'validate-1'],
            $this->decode($request)['methodCalls'][0]
        );
    }
}
