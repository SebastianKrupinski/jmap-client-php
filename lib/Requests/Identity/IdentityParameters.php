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
namespace JmapClient\Requests\Identity;

use JmapClient\Requests\RequestParameters;

class IdentityParameters extends RequestParameters
{
    public function __construct(&$request, $action, $id) {

        parent::__construct($request, $action, $id);

    }

    public function name(string $value): self {
        
        // creates or updates parameter and assigns value
        $this->parameter('name', $value);
        // return self for function chaining
        return $this;

    }

    public function replyTo(string $address): self {
        
        // creates or updates parameter and assigns value
        $this->parameterCollection('replyTo', (object) ['name' => $name, 'email' => $address]);
        // return self for function chaining
        return $this;

    }

    public function bcc(string $address, string $name = ''): self {
        
        // creates or updates parameter and assigns value
        $this->parameterCollection('bcc', (object) ['name' => $name, 'email' => $address]);
        // return self for function chaining
        return $this;

    }

    public function signature(string $value, string $type): self {
        
        match ($type) {
            'plain' => $this->parameter('textSignature', $value),
            'html' => $this->parameter('htmlSignature', $value),
        };
        // return self for function chaining
        return $this;

    }

    public function signaturePlain(string $value): self {
        
        return $this->contents($value, 'plain');

    }

    public function signatureHtml(string $value): self {
        
        return $this->contents($value, 'html');

    }

}
