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
namespace JmapClient\Responses\Tasks;

use DateTimeImmutable;
use JmapClient\Responses\ResponseParameters;

class TaskParameters extends ResponseParameters
{
    
    public function __construct(array $response = []) {

        parent::__construct($response);

    }

    /* Metadata Properties */

    public function in(): array|null {
        
        // return value of parameter
        return array_keys($this->parameter('taskListId'));

    }
    
    public function id(): string|null {

        return $this->parameter('id');

    }

    public function uid(): string|null {
        
        return $this->parameter('uid');

    }

    public function created(): DateTimeImmutable|null {
        
        $value = $this->parameter('created');
        return ($value) ? new DateTimeImmutable($value) : null;

    }

    public function updated(): DateTimeImmutable|null {
        
        $value = $this->parameter('updated');
        return ($value) ? new DateTimeImmutable($value) : null;

    }

}
