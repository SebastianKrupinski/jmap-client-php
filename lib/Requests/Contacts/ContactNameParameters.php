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
namespace JmapClient\Requests\Contacts;

use JmapClient\Requests\RequestParameters;

class ContactNameParameters extends RequestParameters {

    public function __construct(&$parameters = null) {
        parent::__construct($parameters);
        $this->parameter('@type', 'Name');
    }

    public function type(string $value): static {
        $this->parameter('@type', $value);
        return $this;
    }

    public function full(string $value): static {
        $this->parameter('full', $value);
        return $this;
    }

    public function components(?int $index = null): ContactComponentParameters {
        if (!isset($this->_parameters->components)) {
            $this->parameter('components', []);
        }
        if ($index) {
            if (!isset($this->_parameters->components[$index])){
                $this->_parameters->components[$index] = new \stdClass();
            }
            return new ContactComponentParameters($this->_parameters->components[$index]);
        } else {
            $this->_parameters->components[] = new \stdClass();
            return new ContactComponentParameters(
                $this->_parameters->components[array_key_last($this->_parameters->components)]
            );
        }

    }

    public function separator(string $value): static {
        $this->parameter('defaultSeparator', $value);
        return $this;
    }

    public function ordered(bool $value): static {
        $this->parameter('isOrdered', $value);
        return $this;
    }

    public function sorting(string $component, string $value): static {
        if (!isset($this->_parameters->sortAs?->$component)) {
            $this->parameterStructured('sortAs', $component, $value);
        }
        return $this;
    }

}
