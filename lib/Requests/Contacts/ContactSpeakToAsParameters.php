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

class ContactSpeakToAsParameters extends RequestParameters {
    
    public function __construct(&$parameters = null) {
        parent::__construct($parameters);
        $this->parameter('@type', 'SpeakToAs');
    }

    public function type(string $value): static {
        $this->parameter('@type', $value);
        return $this;
    }

    /**
     * Set the grammatical gender for addressing this contact
     * Standard values: animate, common, feminine, inanimate, masculine, neuter
     * 
     * @param string $value
     * @return static
     */
    public function grammaticalGender(string $value): static {
        $this->parameter('grammaticalGender', $value);
        return $this;
    }

    /**
     * Add a pronouns entry for this contact
     * 
     * @param string $id
     * @return ContactPronounsParameters
     */
    public function pronouns(string $id): ContactPronounsParameters {
        if (!isset($this->_parameters->pronouns?->$id)) {
            $this->parameterStructured('pronouns', $id, new \stdClass());
        }
        return new ContactPronounsParameters($this->_parameters->pronouns->$id);
    }

}
