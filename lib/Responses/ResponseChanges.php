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

use JmapClient\Responses\Response;

class ResponseChanges extends Response
{

    public function __construct (array $response = []) {

        parent::__construct($response);

    }

    public function stateOld(): string {
        return (isset($this->_response[1]['oldState'])) ? $this->_response[1]['oldState'] : '';
    }

    public function stateNew(): string {
        return (isset($this->_response[1]['newState'])) ? $this->_response[1]['newState'] : '';
    }

    public function hasMore(): bool {
        return (isset($this->_response[1]['hasMoreChanges'])) ? filter_var($this->_response[1]['hasMoreChanges'], FILTER_VALIDATE_BOOLEAN) : false;
    }

    public function created(): array {
        return (isset($this->_response[1]['created'])) ? $this->_response[1]['created'] : [];
    }

    public function updated(): array {
        return (isset($this->_response[1]['updated'])) ? $this->_response[1]['updated'] : [];
    }

    public function deleted(): array {
        return (isset($this->_response[1]['destroyed'])) ? $this->_response[1]['destroyed'] : [];
    }

}
