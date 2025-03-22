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
namespace JmapClient\Requests\Contacts;

use JmapClient\Requests\RequestParameters;

class ContactAnniversaryParameters extends RequestParameters
{
    public function __construct(&$parameters = null) {

        parent::__construct($parameters);

        $this->parameter('@type', 'Anniversary');

    }

    public function type(string $value): static {

        $this->parameter('@type', $value);
        return $this;

    }

    public function dateStamp(): ContactDateStampParameters {
        
        if (!isset($this->_parameters->date) || $this->_parameters->date?->{'@type'} !== 'Timestamp') {
            $this->parameter('date', new \stdClass());
        }

        return new ContactDateStampParameters($this->_parameters->date);

    }

    public function datePartial(): ContactDatePartialParameters {
        
        if (!isset($this->_parameters->date) || $this->_parameters->date?->{'@type'} !== 'PartialDate') {
            $this->parameter('date', new \stdClass());
        }

        return new ContactDatePartialParameters($this->_parameters->date);

    }

    public function kind(string $value): static {

        $this->parameter('kind', $value);
        return $this;

    }

    public function place(): ContactAddressParameters {
        
        if (!isset($this->_parameters->place)) {
            $this->parameter('place', new \stdClass());
        }

        return new ContactAddressParameters($this->_parameters->place);

    }

}
