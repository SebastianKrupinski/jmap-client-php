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

class ResponseBundle
{
    protected array $_map = [
        'Mailbox/get' => 'JmapClient\Responses\Mail\MailboxGet',
        'Mailbox/set' => 'JmapClient\Responses\Mail\MailboxSet',
        'Mailbox/changes' => 'JmapClient\Responses\Mail\MailboxChanges',
        'Mailbox/query' => 'JmapClient\Responses\Mail\MailboxQuery',
        'Mailbox/queryChanges' => 'JmapClient\Responses\Mail\MailboxQueryChanges',
        'Email/get' => 'JmapClient\Responses\Mail\MailGet',
        'Email/set' => 'JmapClient\Responses\Mail\MailSet',
        'Email/changes' => 'JmapClient\Responses\Mail\MailChanges',
        'Email/query' => 'JmapClient\Responses\Mail\MailQuery',
        'Email/queryChanges' => 'JmapClient\Responses\Mail\MailQueryChanges',
    ];
    protected array $_response = [];

    public function __construct (array $response = []) {
        
        $this->_response = $response;

        foreach ($this->_response['methodResponses'] as $key => $entry) {
            if (isset($this->_map[$entry[0]])) {
                $class = $this->_map[$entry[0]];
                $this->_response['methodResponses'][$key] = new $class($entry);
            }
        }

    }

    public function responses(): array {

        return (isset($this->_response['methodResponses'])) ? $this->_response['methodResponses'] : [];

    }

    public function response(int $position): mixed {

        return (isset($this->_response['methodResponses'])) ? $this->_response['methodResponses'][$position] : null;

    }

    public function state(): string {

        return (isset($this->_response['sessionState'])) ? $this->_response['sessionState'] : '';

    }

    public function tally(): int {

        return (isset($this->_response['methodResponses'])) ? count($this->_response['methodResponses']) : 0;

    }

}
