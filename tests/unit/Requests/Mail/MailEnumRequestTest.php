<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Tests\Unit\Requests\Mail;

use JmapClient\Enums\Mail\MailboxRole;
use JmapClient\Enums\Mail\MailKeyword;
use JmapClient\Requests\Mail\MailboxQuery;
use JmapClient\Requests\Mail\MailboxSet;
use JmapClient\Requests\Mail\MailQuery;
use JmapClient\Requests\Mail\MailSet;
use JmapClient\Requests\RequestBundle;
use PHPUnit\Framework\TestCase;

class MailEnumRequestTest extends TestCase
{
    private function command(...$requests): array
    {
        return array_map(
            fn ($call) => $call[1],
            json_decode((new RequestBundle(...$requests))->jsonEncode(), true, 512, JSON_THROW_ON_ERROR)['methodCalls']
        );
    }

    public function testMailboxRoleAcceptsEnumAndCustomString(): void
    {
        $set = new MailboxSet('account-1');
        $set->create('m1')->role(MailboxRole::Archive);
        $set->create('m2')->role('x-custom');
        $set->create('m3')->role(null);
        $query = new MailboxQuery('account-1');
        $query->filter()->role(MailboxRole::Inbox);

        [$setCommand, $queryCommand] = $this->command($set, $query);

        $this->assertSame('archive', $setCommand['create']['m1']['role']);
        $this->assertSame('x-custom', $setCommand['create']['m2']['role']);
        $this->assertNull($setCommand['create']['m3']['role']);
        $this->assertSame(['role' => 'inbox'], $queryCommand['filter']);
    }

    public function testKeywordAcceptsEnumAndCustomString(): void
    {
        $set = new MailSet('account-1');
        $set->create('e1')->keyword(MailKeyword::Important)->keyword('custom-label')->seen();
        $query = new MailQuery('account-1');
        $query->filter()->keywordPresent(MailKeyword::Flagged)->threadKeywordNone('custom-label');
        $query->sort()->keyword(MailKeyword::Seen, true);

        [$setCommand, $queryCommand] = $this->command($set, $query);

        $this->assertSame(['$important' => true, 'custom-label' => true, '$seen' => true], $setCommand['create']['e1']['keywords']);
        $this->assertSame(['hasKeyword' => '$flagged', 'noneInThreadHaveKeyword' => 'custom-label'], $queryCommand['filter']);
        $this->assertSame([['property' => 'hasKeyword', 'isAscending' => true, 'keyword' => '$seen']], $queryCommand['sort']);
    }
}
