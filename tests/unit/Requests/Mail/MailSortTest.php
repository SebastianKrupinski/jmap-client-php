<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Tests\Unit\Requests\Mail;

use JmapClient\Requests\Mail\MailQuery;
use JmapClient\Requests\RequestBundle;
use PHPUnit\Framework\TestCase;

class MailSortTest extends TestCase
{
    private function sort(MailQuery $request): array
    {
        return json_decode((new RequestBundle($request))->jsonEncode(), true, 512, JSON_THROW_ON_ERROR)['methodCalls'][0][1]['sort'];
    }

    public function testKeywordSortSendsKeyword(): void
    {
        $request = new MailQuery('account-1', 'query-1');
        $request->sort()->keyword('$flagged', false)->received(false);

        $this->assertSame([
            ['property' => 'hasKeyword', 'isAscending' => false, 'keyword' => '$flagged'],
            ['property' => 'receivedAt', 'isAscending' => false],
        ], $this->sort($request));
    }

    public function testKeywordSortDefaultsToDescending(): void
    {
        $request = new MailQuery('account-1', 'query-1');
        $request->sort()->keyword('$seen');

        $this->assertSame([
            ['property' => 'hasKeyword', 'isAscending' => false, 'keyword' => '$seen'],
        ], $this->sort($request));
    }
}
