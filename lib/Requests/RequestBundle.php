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
namespace JmapClient\Requests;

class RequestBundle
{
    protected array $_requests = ['using' => [], 'methodCalls' => []];

    public function __construct (array $spaces = [], array $requests = []) {
        $this->_requests['using'] = $spaces;
        $this->_requests['methodCalls'] = $requests;
    }

    public function appendRequest(string $space, object $request): self {

        // check if name space exist already
        if (array_search($space, $this->_requests['using']) === false) {
            $this->_requests['using'][] = $space;
        }
        // append request to collection
        $this->_requests['methodCalls'][] = $request;

        return $this;

    }

    public function removeRequest(int $index): self {

        // evaluate if request exists in collection
        if (isset($this->_requests['methodCalls'][$index])) {
            // remove request from collection
            unset($this->_requests['methodCalls'][$index]);
            // Re-index collection
            $this->_requests['methodCalls'] = array_values($this->_requests['methodCalls']); 
        }
        
        return $this;

    }

    public function phrase(): string {

        return json_encode($this->_requests, JSON_UNESCAPED_SLASHES);

    }

}
