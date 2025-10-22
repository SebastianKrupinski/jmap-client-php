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

class ContactSpeakToAsParameters extends ResponseParameters {

    public function type(): string|null {
        return $this->parameter('@type');
    }

    /**
     * Get the grammatical gender for addressing the contact
     * Values: animate, common, feminine, inanimate, masculine, neuter
     * 
     * @return string|null
     */
    public function grammaticalGender(): string|null {
        return $this->parameter('grammaticalGender');
    }

    /**
     * Get the pronouns for this contact
     * Returns an array of ContactPronounsParameters indexed by ID
     * 
     * @return array|null
     */
    public function pronouns(): array|null {
        $collection = $this->parameter('pronouns') ?? [];
        foreach ($collection as $key => $data) {
            $collection[$key] = new ContactPronounsParameters($data);
        }
        return $collection;
    }

}
