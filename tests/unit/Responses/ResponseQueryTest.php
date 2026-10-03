<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Tests\Unit\Responses;

use JmapClient\Responses\ResponseQuery;
use PHPUnit\Framework\TestCase;

class ResponseQueryTest extends TestCase
{
    public function testExposesPagingProperties(): void
    {
        $response = new ResponseQuery([
            'ContactCard/query',
            [
                'accountId' => 'account-1',
                'queryState' => 'state-1',
                'canCalculateChanges' => true,
                'position' => 500,
                'total' => 1004,
                'limit' => 500,
                'ids' => ['a', 'b'],
            ],
            'query-1',
        ]);

        $this->assertSame('state-1', $response->state());
        $this->assertSame(['a', 'b'], $response->list());
        $this->assertSame(500, $response->position());
        $this->assertSame(1004, $response->total());
        $this->assertSame(500, $response->limit());
    }

    public function testOptionalPagingPropertiesDefault(): void
    {
        $response = new ResponseQuery([
            'ContactCard/query',
            ['accountId' => 'account-1', 'queryState' => 'state-1', 'position' => 0, 'ids' => []],
            'query-1',
        ]);

        $this->assertSame(0, $response->position());
        $this->assertNull($response->total());
        $this->assertNull($response->limit());
    }
}
