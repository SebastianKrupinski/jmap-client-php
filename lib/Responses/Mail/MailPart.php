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
namespace JmapClient\Responses\Mail;

class MailPart
{
    
    protected array $_part = [];
    protected array $_subparts = [];

    public function __construct(array $part = []) {
        if (isset($part['subParts'])) {
            foreach ($part['subParts'] as $entry) {
                $this->_subparts[] = new MailPart($entry);
            }
            unset($part['subParts']);
        }
        $this->_part = $part;
    }

    public function id(): string|null {
        
        // return value of parameter
        return $this->_part['partId'];

    }

    public function blob(): string|null {
        
        // return value of parameter
        return $this->_part['blobId'];

    }

    public function disposition(): string|null {
        
        // return value of parameter
        return $this->_part['disposition'];

    }
    
    public function type(): string|null {
        
        // return value of parameter
        return $this->_part['type'];

    }

    public function charset(): string|null {
        
        // return value of parameter
        return $this->_part['charset'];

    }

    public function name(): string|null {
        
        // return value of parameter
        return $this->_part['name'];

    }

    public function location(): string|null {
        
        // return value of parameter
        return $this->_part['location'];

    }

    public function cid(): string|null {
        
        // return value of parameter
        return $this->_part['cid'];

    }

    public function size(): int|null {
        
        // return value of parameter
        return $this->_part['size'];

    }

    public function imageSize(): int|null {
        
        // return value of parameter
        return $this->_part['imageSize'];

    }

    public function parts(): array|null {
        
        // return value of parameter
        return $this->_subparts;

    }

}
