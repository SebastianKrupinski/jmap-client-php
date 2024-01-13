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
namespace JmapClient\Requests\Mail;

use JmapClient\Requests\RequestParameters;

class MailParameters extends RequestParameters
{
    public function __construct(&$request, $action, $id) {

        parent::__construct($request, $action, $id);

    }

    public function in(string $value): self {
        
        // creates or updates parameter and assigns value
        $this->parameterStructured('mailboxIds', $value, true);
        // return self for function chaining
        return $this;

    }

    public function from(string $address, string $name = ''): self {
        
        // creates or updates parameter and assigns value
        $this->parameterCollection('from', (object) ['name' => $name, 'email' => $address]);
        // return self for function chaining
        return $this;

    }

    public function to(string $address, string $name = ''): self {
        
        // creates or updates parameter and assigns value
        $this->parameterCollection('to', (object) ['name' => $name, 'email' => $address]);
        // return self for function chaining
        return $this;

    }

    public function cc(string $address, string $name = ''): self {
        
        // creates or updates parameter and assigns value
        $this->parameterCollection('cc', (object) ['name' => $name, 'email' => $address]);
        // return self for function chaining
        return $this;

    }

    public function bcc(string $address, string $name = ''): self {
        
        // creates or updates parameter and assigns value
        $this->parameterCollection('bcc', (object) ['name' => $name, 'email' => $address]);
        // return self for function chaining
        return $this;

    }

    public function subject(string $value): self {
        
        // creates or updates parameter and assigns value
        $this->parameter('subject', $value);
        // return self for function chaining
        return $this;

    }

    public function contents(string $content, string $type, ?string $id = null): self {
        
        // evaluate if id was manually set
        if (empty($id)) {
            // generate unique id
            $id = uniqid();
        }
        // creates or updates parameter and assigns value
        $this->parameterStructured('bodyStructure', 'partId', $id);
        $this->parameterStructured('bodyStructure', 'type', $id);
        // creates or updates parameter and assigns value
        $this->parameterStructured('bodyValues', $id, (object) ['value' => $value, 'isTruncated' => false]);
        // return self for function chaining
        return $this;

    }

    public function contentsPlain(string $content, string $id = null): self {
        
        return $this->contents($content, 'text/plain', $id);

    }

    public function contentsHtml(string $content, string $id = null): self {
        
        return $this->contents($content, 'text/html', $id);

    }

    public function draft(): self {
        
        // creates or updates parameter and assigns value
        $this->parameterStructured('keywords', '$draft', true);
        // return self for function chaining
        return $this;

    }

    public function seen(): self {
        
        // creates or updates parameter and assigns value
        $this->parameterStructured('keywords', '$seen', true);
        // return self for function chaining
        return $this;

    }

    public function flagged(): self {
        
        // creates or updates parameter and assigns value
        $this->parameterStructured('keywords', '$flagged', true);
        // return self for function chaining
        return $this;

    }

    public function answered(): self {
        
        // creates or updates parameter and assigns value
        $this->parameterStructured('keywords', '$answered', true);
        // return self for function chaining
        return $this;

    }

    public function forwarded(): self {
        
        // creates or updates parameter and assigns value
        $this->parameterStructured('keywords', '$forwarded', true);
        // return self for function chaining
        return $this;

    }

}
