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
namespace JmapClient\Responses\Contacts;

use JmapClient\Responses\ResponseParameters;

class ContactPronounsParameters extends ResponseParameters {

    public function type(): string|null {
        return $this->parameter('@type');
    }

    /**
     * Get the pronouns string
     * Examples: "she/her", "they/them", "xe/xir"
     * 
     * @return string|null
     */
    public function pronouns(): string|null {
        return $this->parameter('pronouns');
    }

    /**
     * Get the contexts for these pronouns
     * Returns array keys from contexts where value is true
     * e.g., ['work' => true, 'private' => true]
     * 
     * @return array|null
     */
    public function contexts(): array|null {
        $value = $this->parameter('contexts');
        return $value ? array_keys((array)$value) : null;
    }

    /**
     * Get the preference for these pronouns (1-100, lower is more preferred)
     * 
     * @return int|null
     */
    public function preference(): int|null {
        return $this->parameter('pref');
    }

}
