<?php
declare(strict_types=1);

/**
* @copyright Copyright (c) 2023 Sebastian Krupinski <krupinski01@gmail.com>
*
* @author Sebastian Krupinski <krupinski01@gmail.com>
*
* @license AGPL-3.0-or-later
*
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU Affero General Public License as
* published by the Free Software Foundation, either version 3 of the
* License, or (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU Affero General Public License for more details.
*
* You should have received a copy of the GNU Affero General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
*
*/
namespace JmapClient\Responses;

class ResponseClasses
{
    static array $Parameters = [
        'Blob' => 'JmapClient\Responses\Blob\BlobParameters',
        'Identity' => 'JmapClient\Responses\Identity\IdentityParameters',
        'Mailbox' => 'JmapClient\Responses\Mail\MailboxParameters',
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
        // Blob
        'Blob/get' => 'JmapClient\Responses\Blob\BlobGet',
        'Blob/upload' => 'JmapClient\Responses\Blob\BlobSet',
        // Mail Identities
        'Identity/get' => 'JmapClient\Responses\Identity\IdentityGet',
        'Identity/set' => 'JmapClient\Responses\Identity\IdentitySet',
        'Identity/changes' => 'JmapClient\Responses\Identity\IdentityChanges',
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
        // Contact Entity
        'Contact/get' => 'JmapClient\Responses\Contacts\ContactGet',
        'Contact/set' => 'JmapClient\Responses\Contacts\ContactSet',
        'Contact/changes' => 'JmapClient\Responses\Contacts\ContactChanges',
        'Contact/query' => 'JmapClient\Responses\Contacts\ContactQuery',
        'Contact/queryChanges' => 'JmapClient\Responses\Contacts\ContactQueryChanges',
    ];

}
