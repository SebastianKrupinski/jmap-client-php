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

class AddressBookParameters extends ResponseParameters
{
    
    public function __construct(array $response = []) {

        parent::__construct($response);

    }

    public function id(): string|null {
        
        // return value of parameter
        return $this->parameter('id');

    }

    public function name(): string|null {
        
        // return value of parameter
        return $this->parameter('name');

    }

    public function description(): string|null {
        
        // return value of parameter
        return $this->parameter('description');

    }

    public function color(): string|null {
        
        // return value of parameter
        return $this->parameter('color');

    }

    public function priority(): int|null {
        
        // return value of parameter
        return $this->parameter('sortOrder');

    }

    public function subscribed(): bool|null {
        
        // return value of parameter
        return $this->parameter('isSubscribed');

    }

    public function visible(): bool|null {
        
        // return value of parameter
        return $this->parameter('isVisible');

    }

    public function default(): bool|null {
        
        // return value of parameter
        return $this->parameter('isDefault');

    }

    public function timezone(): string|null {
        
        // return value of parameter
        return $this->parameter('timeZone');

    }

    public function sharees(): array|null {
        
        // return value of parameter
        return $this->parameter('shareWith');

    }

    public function rights(): object|null {
        
        // return value of parameter
        return $this->parameter('myRights');

    }

}
