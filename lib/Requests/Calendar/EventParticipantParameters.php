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
namespace JmapClient\Requests\Calendar;

use JmapClient\Requests\RequestParameters;

class EventParticipantParameters extends RequestParameters {

    public function __construct(&$parameters = null) {
        parent::__construct($parameters);
        $this->parameter('@type', 'Participant');
    }
    
    public function type(string $value): static {
        $this->parameter('@type', $value);
        return $this;
    }

    public function kind(string $value): self {
        $this->parameter('kind', $value);
        return $this;
    }

    public function name(string $value): self {
        $this->parameter('name', $value);
        return $this;
    }

    public function description(string $value): self {
        $this->parameter('description', $value);
        return $this;
    }

    public function address(string $value): self {
        $this->parameter('email', $value);
        return $this;
    }

    public function send(string $protocol, string $value): self {
        $this->parameterStructured('sendTo', $protocol, $value);
        return $this;
    }

    public function status(string $value): self {
        $this->parameter('participationStatus', $value);
        return $this;
    }

    public function comment(string $value): self {
        $this->parameter('participationComment', $value);
        return $this;
    }

    public function roles(string ...$value): self {
        foreach ($value as $entry) {
            $collection[$entry] = true;
        }
        $this->parameter('roles', (object)$collection);
        return $this;
    }

}
