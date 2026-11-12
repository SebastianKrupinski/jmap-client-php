<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Tests\Unit\Requests;

use JmapClient\Requests\Calendar\CalendarParameters;
use JmapClient\Requests\Calendar\CalendarPermissions;
use JmapClient\Requests\Calendar\EventCopy;
use JmapClient\Requests\Contacts\AddressBookParameters;
use JmapClient\Requests\Contacts\AddressBookPermissions;
use JmapClient\Requests\Contacts\ContactCopy;
use JmapClient\Requests\Mail\MailboxParameters;
use JmapClient\Requests\Mail\MailboxPermissions;
use JmapClient\Requests\Mail\MailCopy;
use JmapClient\Requests\RequestBundle;
use PHPUnit\Framework\TestCase;

class CopyRequestTest extends TestCase
{
    private function decode(RequestBundle $bundle): array
    {
        return json_decode($bundle->jsonEncode(), true, 512, JSON_THROW_ON_ERROR);
    }

    public function testMailCopyOverridesMailboxAndKeywords(): void
    {
        $request = new MailCopy('account-1', 'copy-1');
        $request->sourceAccount('account-2')->destroySource(true);
        $request->copy('k1', 'email-1')->in('mailbox-1')->flagged();

        $this->assertSame([
            'using' => ['urn:ietf:params:jmap:core', 'urn:ietf:params:jmap:mail'],
            'methodCalls' => [[
                'Email/copy',
                [
                    'accountId' => 'account-1',
                    'fromAccountId' => 'account-2',
                    'onSuccessDestroyOriginal' => true,
                    'create' => [
                        'k1' => ['id' => 'email-1', 'mailboxIds' => ['mailbox-1' => true], 'keywords' => ['$flagged' => true]],
                    ],
                ],
                'copy-1',
            ]],
        ], $this->decode(new RequestBundle($request)));
    }

    public function testContactCopyOverridesAddressBook(): void
    {
        $request = new ContactCopy('account-1', 'copy-1');
        $request->sourceAccount('account-2');
        $request->copy('k1', 'contact-1')->in('book-1');

        $call = $this->decode(new RequestBundle($request))['methodCalls'][0];
        $this->assertSame('ContactCard/copy', $call[0]);
        $this->assertSame([
            'accountId' => 'account-1',
            'fromAccountId' => 'account-2',
            'create' => ['k1' => ['id' => 'contact-1', '@type' => 'Card', 'version' => '1.0', 'addressBookIds' => ['book-1' => true]]],
        ], $call[1]);
    }

    public function testEventCopyOverridesCalendar(): void
    {
        $request = new EventCopy('account-1', 'copy-1');
        $request->sourceAccount('account-2')->sourceState('s1')->destinationState('s2');
        $request->copy('k1', 'event-1')->in('calendar-1');

        $call = $this->decode(new RequestBundle($request))['methodCalls'][0];
        $this->assertSame('CalendarEvent/copy', $call[0]);
        $this->assertSame([
            'accountId' => 'account-1',
            'fromAccountId' => 'account-2',
            'ifFromInState' => 's1',
            'ifInState' => 's2',
            'create' => ['k1' => ['id' => 'event-1', '@type' => 'Event', 'calendarIds' => ['calendar-1' => true]]],
        ], $call[1]);
    }

    public function testMailboxShareesSerializeRights(): void
    {
        $parameters = new MailboxParameters();
        $parameters->sharees('user-2', (new MailboxPermissions())->readItems(true)->addItems(false)->share(false));
        $parameters->sharees('user-3')->readItems(true)->setSeen(true);

        $this->assertSame([
            'shareWith' => [
                'user-2' => ['mayReadItems' => true, 'mayAddItems' => false, 'mayShare' => false],
                'user-3' => ['mayReadItems' => true, 'maySetSeen' => true],
            ],
        ], $this->raw($parameters));
    }

    public function testCalendarAndAddressBookShareesKeepPassedPermissions(): void
    {
        $calendar = new CalendarParameters();
        $calendar->sharees('user-2', (new CalendarPermissions())->read(true)->write(false));
        $calendar->sharees('user-3')->read(true);

        $book = new AddressBookParameters();
        $book->sharees('user-2', (new AddressBookPermissions())->read(true)->delete(false));

        $this->assertSame([
            'user-2' => ['mayReadItems' => true, 'mayWriteAll' => false],
            'user-3' => ['mayReadItems' => true],
        ], $this->raw($calendar)['shareWith']);
        $this->assertSame([
            'user-2' => ['mayRead' => true, 'mayDelete' => false],
        ], $this->raw($book)['shareWith']);
    }

    private function raw(object $parameters): array
    {
        $property = new \ReflectionProperty($parameters, '_parameters');
        return json_decode(json_encode($property->getValue($parameters)), true);
    }
}
