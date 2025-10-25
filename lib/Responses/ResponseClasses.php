<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient\Responses;

class ResponseClasses
{
    static array $Parameters = [
        'PushSubscription' => 'JmapClient\Responses\Core\SubscriptionParameters',
        'Blob' => 'JmapClient\Responses\Blob\BlobParameters',
        'Mailbox' => 'JmapClient\Responses\Mail\MailboxParameters',
        'Identity' => 'JmapClient\Responses\Mail\MailIdentityParameters',
        'Email' => 'JmapClient\Responses\Mail\MailParameters',
        'EmailSubmission' => 'JmapClient\Responses\Mail\MailSubmissionParameters',
        'Calendar' => 'JmapClient\Responses\Calendar\CalendarParameters',
        'CalendarEvent' => 'JmapClient\Responses\Calendar\EventParameters',
        'AddressBook' => 'JmapClient\Responses\Contacts\AddressBookParameters',
        'ContactCard' => 'JmapClient\Responses\Contacts\ContactParameters',
    ];

    static array $Commands = [
        // errors
        'error' => 'JmapClient\Responses\ResponseException',
        // Core
        'PushSubscription/get' => 'JmapClient\Responses\Core\SubscriptionGet',
        'PushSubscription/set' => 'JmapClient\Responses\Core\SubscriptionSet',
        // Blob
        'Blob/get' => 'JmapClient\Responses\Blob\BlobGet',
        'Blob/upload' => 'JmapClient\Responses\Blob\BlobSet',
        // Mail Identities
        'Identity/get' => 'JmapClient\Responses\Mail\MailIdentityGet',
        'Identity/set' => 'JmapClient\Responses\Mail\MailIdentitySet',
        'Identity/changes' => 'JmapClient\Responses\Mail\MailIdentityChanges',
        // Mail Collections
        'Mailbox/get' => 'JmapClient\Responses\Mail\MailboxGet',
        'Mailbox/set' => 'JmapClient\Responses\Mail\MailboxSet',
        'Mailbox/changes' => 'JmapClient\Responses\Mail\MailboxChanges',
        'Mailbox/query' => 'JmapClient\Responses\Mail\MailboxQuery',
        'Mailbox/queryChanges' => 'JmapClient\Responses\Mail\MailboxQueryChanges',
        // Mail Entity
        'Email/get' => 'JmapClient\Responses\Mail\MailGet',
        'Email/set' => 'JmapClient\Responses\Mail\MailSet',
        'Email/changes' => 'JmapClient\Responses\Mail\MailChanges',
        'Email/query' => 'JmapClient\Responses\Mail\MailQuery',
        'Email/queryChanges' => 'JmapClient\Responses\Mail\MailQueryChanges',
        'Email/parse' => 'JmapClient\Responses\Mail\MailParse',
        // Mail Sending
        'EmailSubmission/get' => 'JmapClient\Responses\Mail\MailSubmissionGet',
        'EmailSubmission/set' => 'JmapClient\Responses\Mail\MailSubmissionSet',
        'EmailSubmission/changes' => 'JmapClient\Responses\Mail\MailSubmissionChanges',
        'EmailSubmission/query' => 'JmapClient\Responses\Mail\MailSubmissionQuery',
        'EmailSubmission/queryChanges' => 'JmapClient\Responses\Mail\MailSubmissionQueryChanges',
        // Mail Thread
        'Thread/get' => 'JmapClient\Responses\Mail\MailThreadGet',
        'Thread/changes' => 'JmapClient\Responses\Mail\MailThreadChanges',
        // Contact Collection
        'AddressBook/get' => 'JmapClient\Responses\Contacts\AddressBookGet',
        'AddressBook/set' => 'JmapClient\Responses\Contacts\AddressBookSet',
        'AddressBook/changes' => 'JmapClient\Responses\Contacts\AddressBookChanges',
        // Contact Entity
        'ContactCard/get' => 'JmapClient\Responses\Contacts\ContactGet',
        'ContactCard/set' => 'JmapClient\Responses\Contacts\ContactSet',
        'ContactCard/changes' => 'JmapClient\Responses\Contacts\ContactChanges',
        'ContactCard/query' => 'JmapClient\Responses\Contacts\ContactQuery',
        'ContactCard/queryChanges' => 'JmapClient\Responses\Contacts\ContactQueryChanges',
        // Calendar Collection
        'Calendar/get' => 'JmapClient\Responses\Calendar\CalendarGet',
        'Calendar/set' => 'JmapClient\Responses\Calendar\CalendarSet',
        'Calendar/changes' => 'JmapClient\Responses\Calendar\CalendarChanges',
        // Calendar Entity
        'CalendarEvent/get' => 'JmapClient\Responses\Calendar\EventGet',
        'CalendarEvent/set' => 'JmapClient\Responses\Calendar\EventSet',
        'CalendarEvent/changes' => 'JmapClient\Responses\Calendar\EventChanges',
        'CalendarEvent/query' => 'JmapClient\Responses\Calendar\EventQuery',
        'CalendarEvent/queryChanges' => 'JmapClient\Responses\Calendar\EventQueryChanges',
        // Task Collection
        'TaskList/get' => 'JmapClient\Responses\Tasks\TaskListGet',
        'TaskList/set' => 'JmapClient\Responses\Tasks\TaskListSet',
        'TaskList/changes' => 'JmapClient\Responses\Tasks\TaskListChanges',
        // Task Entity
        'Task/get' => 'JmapClient\Responses\Tasks\TaskGet',
        'Task/set' => 'JmapClient\Responses\Tasks\TaskSet',
        'Task/changes' => 'JmapClient\Responses\Tasks\TaskChanges',
        'Task/query' => 'JmapClient\Responses\Tasks\TaskQuery',
        'Task/queryChanges' => 'JmapClient\Responses\Tasks\TaskQueryChanges',
    ];

}
