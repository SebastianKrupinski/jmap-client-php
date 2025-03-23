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

class ResponseSet extends Response {

    public function stateOld(): string {
        return (isset($this->_response[self::RESPONSE_OBJECT]['oldState'])) ? $this->_response[self::RESPONSE_OBJECT]['oldState'] : '';
    }

    public function stateNew(): string {
        return (isset($this->_response[self::RESPONSE_OBJECT]['newState'])) ? $this->_response[self::RESPONSE_OBJECT]['newState'] : '';
    }

    public function created(): array {
        return (isset($this->_response[self::RESPONSE_OBJECT]['created'])) ? $this->_response[self::RESPONSE_OBJECT]['created'] : [];
    }

    public function updated(): array {
        return (isset($this->_response[self::RESPONSE_OBJECT]['updated'])) ? $this->_response[self::RESPONSE_OBJECT]['updated'] : [];
    }

    public function deleted(): array {
        return (isset($this->_response[self::RESPONSE_OBJECT]['destroyed'])) ? $this->_response[self::RESPONSE_OBJECT]['destroyed'] : [];
    }

}
