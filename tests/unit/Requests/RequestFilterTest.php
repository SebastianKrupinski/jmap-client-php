<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Tests\Unit\Requests;

use DateTimeImmutable;
use JmapClient\Requests\Calendar\EventQuery;
use JmapClient\Requests\Mail\MailFilter;
use JmapClient\Requests\Mail\MailQuery;
use JmapClient\Requests\RequestBundle;
use PHPUnit\Framework\TestCase;

class RequestFilterTest extends TestCase
{
    private function filter(MailQuery|EventQuery $request): array
    {
        return json_decode((new RequestBundle($request))->jsonEncode(), true, 512, JSON_THROW_ON_ERROR)['methodCalls'][0][1]['filter'];
    }

    public function testConditionsJoinWithOr(): void
    {
        $request = new MailQuery('account-1', 'query-1');
        $filter = $request->filter();

        $filter->any($filter->fresh()->from('ann'), $filter->fresh()->to('ann'));

        $this->assertSame([
            'operator' => 'OR',
            'conditions' => [['from' => 'ann'], ['to' => 'ann']],
        ], $this->filter($request));
    }

    public function testOperatorsNestAndKeepTheirKindsFormatting(): void
    {
        $request = new MailQuery('account-1', 'query-1');
        $filter = $request->filter();
        $either = $filter->fresh()->any($filter->fresh()->subject('invoice'), $filter->fresh()->subject('receipt'));
        $notSpam = $filter->fresh()->none($filter->fresh()->keywordPresent('$junk'));

        // (subject invoice OR receipt) AND before a date AND NOT junk
        $filter->all($either, $filter->fresh()->receivedBefore(new DateTimeImmutable('2026-01-05T13:00:00+01:00')), $notSpam);

        $this->assertSame([
            'operator' => 'AND',
            'conditions' => [
                ['operator' => 'OR', 'conditions' => [['subject' => 'invoice'], ['subject' => 'receipt']]],
                ['before' => '2026-01-05T12:00:00Z'],
                ['operator' => 'NOT', 'conditions' => [['hasKeyword' => '$junk']]],
            ],
        ], $this->filter($request));
    }

    public function testAFilterMayBeAmongItsOwnConditions(): void
    {
        $request = new MailQuery('account-1', 'query-1');
        $filter = $request->filter()->in('inbox');

        $filter->any($filter, $filter->fresh()->in('archive'));

        $this->assertSame([
            'operator' => 'OR',
            'conditions' => [['inMailbox' => 'inbox'], ['inMailbox' => 'archive']],
        ], $this->filter($request));
    }

    public function testAFreshFilterIsOfTheSameKindAndAttachedToNoRequest(): void
    {
        $request = new MailQuery('account-1', 'query-1');
        $filter = $request->filter()->in('inbox');

        $fresh = $filter->fresh()->in('archive');

        $this->assertInstanceOf(MailFilter::class, $fresh);
        $this->assertSame(['inMailbox' => 'inbox'], $this->filter($request));
    }

    public function testEveryKindOfFilterJoins(): void
    {
        $request = new EventQuery('account-1', 'query-1');
        $filter = $request->filter();

        $filter->any($filter->fresh()->in('work'), $filter->fresh()->in('home'));

        $this->assertSame([
            'operator' => 'OR',
            'conditions' => [['inCalendar' => 'work'], ['inCalendar' => 'home']],
        ], $this->filter($request));
    }

    public function testAnOperatorNeedsAFilter(): void
    {
        $filter = (new MailQuery('account-1', 'query-1'))->filter();

        foreach (['all', 'any', 'none'] as $method) {
            try {
                $filter->$method();
                $this->fail("$method() accepted no filters");
            } catch (\InvalidArgumentException) {
                $this->addToAssertionCount(1);
            }
        }
    }
}
