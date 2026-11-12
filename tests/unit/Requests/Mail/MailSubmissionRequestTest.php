<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Tests\Unit\Requests\Mail;

use DateTimeImmutable;
use DateTimeZone;
use JmapClient\Requests\Mail\MailSubmissionQuery;
use JmapClient\Requests\Mail\MailSubmissionSet;
use JmapClient\Requests\Mail\MailSubmissionUndoStatus;
use JmapClient\Requests\RequestBundle;
use PHPUnit\Framework\TestCase;

class MailSubmissionRequestTest extends TestCase
{
    private function command(MailSubmissionSet $request): array
    {
        return json_decode((new RequestBundle($request))->jsonEncode(), true, 512, JSON_THROW_ON_ERROR)['methodCalls'][0][1];
    }

    public function testCreateWithEnvelope(): void
    {
        $request = new MailSubmissionSet('account-1', 'set-1');
        $request->create('s1')->identity('identity-1')->message('email-1')->from('sender@example.com')->to(['one@example.com']);

        $this->assertSame([
            'identityId' => 'identity-1',
            'emailId' => 'email-1',
            'envelope' => [
                'mailFrom' => ['email' => 'sender@example.com'],
                'rcptTo' => [['email' => 'one@example.com']],
            ],
        ], $this->command($request)['create']['s1']);
    }

    public function testCreateWithMailFromParameters(): void
    {
        $request = new MailSubmissionSet('account-1', 'set-1');
        $request->create('s1')->from('sender@example.com', ['HOLDFOR' => '3600', 'BODY' => null]);

        $this->assertSame(
            ['email' => 'sender@example.com', 'parameters' => ['HOLDFOR' => '3600', 'BODY' => null]],
            $this->command($request)['create']['s1']['envelope']['mailFrom']
        );
    }

    public function testCancelSetsUndoStatus(): void
    {
        $request = new MailSubmissionSet('account-1', 'set-1');
        $request->update('submission-1')->cancel();

        $this->assertSame(['submission-1' => ['undoStatus' => 'canceled']], $this->command($request)['update']);
    }

    public function testQueryFilters(): void
    {
        $request = new MailSubmissionQuery('account-1', 'query-1');
        $request->filter()
            ->identities('identity-1', 'identity-2')
            ->messages('email-1')
            ->threads('thread-1')
            ->undoStatus(MailSubmissionUndoStatus::Pending)
            ->sendBefore(new DateTimeImmutable('2026-11-12 12:00:00', new DateTimeZone('Europe/Warsaw')))
            ->sendAfter(new DateTimeImmutable('2026-11-01 00:00:00', new DateTimeZone('UTC')));

        $filter = json_decode((new RequestBundle($request))->jsonEncode(), true, 512, JSON_THROW_ON_ERROR)['methodCalls'][0][1]['filter'];

        $this->assertSame([
            'identityIds' => ['identity-1', 'identity-2'],
            'emailIds' => ['email-1'],
            'threadIds' => ['thread-1'],
            'undoStatus' => 'pending',
            'before' => '2026-11-12T11:00:00Z',
            'after' => '2026-11-01T00:00:00Z',
        ], $filter);
    }
}
