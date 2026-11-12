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

class MailFilterTest extends TestCase
{
    private function filter(MailQuery $request): array
    {
        return json_decode((new RequestBundle($request))->jsonEncode(), true, 512, JSON_THROW_ON_ERROR)['methodCalls'][0][1]['filter'];
    }

    public function testKeywordFilters(): void
    {
        $request = new MailQuery('account-1', 'query-1');
        $request->filter()
            ->keywordPresent('$flagged')
            ->keywordAbsent('$seen')
            ->threadKeywordAll('$answered')
            ->threadKeywordSome('$forwarded')
            ->threadKeywordNone('$junk');

        $this->assertSame([
            'hasKeyword' => '$flagged',
            'notKeyword' => '$seen',
            'allInThreadHaveKeyword' => '$answered',
            'someInThreadHaveKeyword' => '$forwarded',
            'noneInThreadHaveKeyword' => '$junk',
        ], $this->filter($request));
    }

    public function testHeaderFilterWithName(): void
    {
        $request = new MailQuery('account-1', 'query-1');
        $request->filter()->header('List-Id');

        $this->assertSame(['header' => ['List-Id']], $this->filter($request));
    }

    public function testHeaderFilterWithNameAndValue(): void
    {
        $request = new MailQuery('account-1', 'query-1');
        $request->filter()->header('Subject', 'invoice');

        $this->assertSame(['header' => ['Subject', 'invoice']], $this->filter($request));
    }
}
