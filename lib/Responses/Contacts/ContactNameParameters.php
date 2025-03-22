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

class ContactNameParameters extends ResponseParameters
{
    
    public function __construct(array $response = []) {

        parent::__construct($response);

    }

    public function type(): string|null {
        
        return $this->parameter('@type');

    }

    public function full(): string|null {
        
        return $this->parameter('full');

    }

    public function components(): array|null {
        
        $collection = $this->parameter('components') ?? [];
        foreach ($collection as $key => $data) {
            $collection[$key] = new ContactComponentParameters($data);
        }

        return $collection;

    }

    public function separator(): string|null {
        
        return $this->parameter('defaultSeparator');

    }

    public function ordered(): bool|null {
        
        return $this->parameter('isOrdered');

    }

    public function sorting(): array|null {
        
        return $this->parameter('sortAs');

    }

}
