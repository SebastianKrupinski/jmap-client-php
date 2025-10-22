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

class ContactPronounsParameters extends RequestParameters {
    
    public function __construct(&$parameters = null) {
        parent::__construct($parameters);
        $this->parameter('@type', 'Pronouns');
    }

    public function type(string $value): static {
        $this->parameter('@type', $value);
        return $this;
    }

    /**
     * Set the pronouns string
     * Examples: "she/her", "they/them", "xe/xir"
     * 
     * @param string $value
     * @return static
     */
    public function pronouns(string $value): static {
        $this->parameter('pronouns', $value);
        return $this;
    }

    /**
     * Set the contexts for these pronouns
     * Standard values: private, work
     * 
     * @param string ...$values
     * @return static
     */
    public function contexts(string ...$values): static {
        $collection = [];
        foreach ($values as $value) {
            $collection[$value] = true;
        }
        $this->parameter('contexts', (object)$collection);
        return $this;
    }

    /**
     * Set the preference for these pronouns (1-100, lower is more preferred)
     * 
     * @param int $value
     * @return static
     */
    public function preference(int $value): static {
        $this->parameter('pref', $value);
        return $this;
    }

}
