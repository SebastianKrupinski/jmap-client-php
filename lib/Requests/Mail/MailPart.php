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

class MailPart extends RequestParameters
{
    protected object $_parameters;

    public function __construct(&$parameters) {

        $this->_parameters =& $parameters;

    }

    public function id(string $value): self {
        
        // creates or updates parameter and assigns value
        $this->_parameters->partId = $value;
        // return self for function chaining
        return $this;

    }

    public function blob(string $value): self {
        
        // creates or updates parameter and assigns value
        $this->_parameters->blobId = $value;
        // return self for function chaining
        return $this;
    
    }

    public function type(string $value): self {
        
        // creates or updates parameter and assigns value
        $this->_parameters->type = $value;
        // return self for function chaining
        return $this;

    }

    public function disposition(string $value): self {
        
        // creates or updates parameter and assigns value
        $this->_parameters->disposition = $value;
        // return self for function chaining
        return $this;

    }

    public function name(string $value): self {
        
        // creates or updates parameter and assigns value
        $this->_parameters->name = $value;
        // return self for function chaining
        return $this;

    }

    public function charset(string $value): self {
        
        // creates or updates parameter and assigns value
        $this->_parameters->charset = $value;
        // return self for function chaining
        return $this;

    }

    public function language(string $value): self {
        
        // creates or updates parameter and assigns value
        $this->_parameters->language = $value;
        // return self for function chaining
        return $this;

    }

    public function location(string $value): self {
        
        // creates or updates parameter and assigns value
        $this->_parameters->location = $value;
        // return self for function chaining
        return $this;

    }

    public function addPart(): MailPart {
        
        // creates or updates parameter and assigns value
        if (!isset($this->_parameters->subParts)) {
            $this->_parameters->subParts = [];
        }

        $this->_parameters->subParts[] = new \stdClass();

        return new MailPart(
            $this->_parameters->subParts[array_key_last($this->_parameters->subParts)]
        );

    }

    public function subParts(): MailPart {
        
        // creates or updates parameter and assigns value
        if (!isset($this->_parameters->subParts)) {
            $this->_parameters->subParts = [];
        }

        return $this->_parameters->subParts;

    }

}
