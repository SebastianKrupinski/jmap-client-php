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
namespace JmapClient\Requests\Blob;

use JmapClient\Requests\RequestParameters;

class BlobParameters extends RequestParameters
{
    public function __construct(object &$parameters) {

        parent::__construct($parameters);

    }

    public function type(string $value): self {
        
        // creates or updates parameter and assigns value
        $this->parameter('type', $value);
        // return self for function chaining
        return $this;

    }

    public function dataPlain(string $value): self {

        // creates or updates parameter and assigns value
        $this->parameterStructured('data', 'data:asText', $value);
        // return self for function chaining
        return $this;

    }

    public function dataEncoded(string $value): self {
        
        // creates or updates parameter and assigns value
        $this->parameterStructured('data', 'data:asBase64', $value);
        // return self for function chaining
        return $this;

    }

}
