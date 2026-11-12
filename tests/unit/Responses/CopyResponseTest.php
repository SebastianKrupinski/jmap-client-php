<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Tests\Unit\Responses;

use JmapClient\Responses\Calendar\EventCopy;
use JmapClient\Responses\Contacts\ContactCopy;
use JmapClient\Responses\Mail\MailboxParameters;
use JmapClient\Responses\Mail\MailCopy;
use JmapClient\Responses\ResponseBundle;
use PHPUnit\Framework\TestCase;

class CopyResponseTest extends TestCase
{
    public static function copyProvider(): array
    {
        return [
            'email' => ['Email/copy', MailCopy::class],
            'contact' => ['ContactCard/copy', ContactCopy::class],
            'event' => ['CalendarEvent/copy', EventCopy::class],
        ];
    }

    /**
     * @dataProvider copyProvider
     */
    public function testCopyResponseIsDeserialized(string $method, string $class): void
    {
        $bundle = new ResponseBundle([
            'methodResponses' => [[
                $method,
                [
                    'fromAccountId' => 'account-2',
                    'accountId' => 'account-1',
                    'oldState' => 'state-1',
                    'newState' => 'state-2',
                    'created' => ['k1' => ['id' => 'item-9']],
                    'notCreated' => ['k2' => ['type' => 'notFound']],
                ],
                'copy-1',
            ]],
            'sessionState' => 'session-1',
        ]);

        $response = $bundle->first();
        $this->assertInstanceOf($class, $response);
        $this->assertSame('account-2', $response->sourceAccount());
        $this->assertSame('account-1', $response->account());
        $this->assertSame('state-2', $response->stateNew());
        $this->assertSame(['id' => 'item-9'], $response->createSuccess('k1'));
        $this->assertSame('notFound', $response->createFailure('k2')['type']);
    }

    public function testMailboxSharingIsDeserialized(): void
    {
        $mailbox = new MailboxParameters([
            'id' => 'mailbox-1',
            'myRights' => ['mayReadItems' => true, 'mayShare' => false],
            'shareWith' => ['user-2' => ['mayReadItems' => true]],
        ]);

        $this->assertTrue($mailbox->rights()->readItems());
        $this->assertFalse($mailbox->rights()->share());
        $this->assertSame(['user-2' => ['mayReadItems' => true]], $mailbox->sharees());
    }
}
