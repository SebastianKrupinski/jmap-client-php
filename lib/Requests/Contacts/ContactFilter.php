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

use DateTime;
use DateTimeImmutable;
use JmapClient\Requests\RequestFilter;

class ContactFilter extends RequestFilter {

    public function in(string ...$value): self {

        // creates or updates parameter and assigns value
        $this->condition('inAddressBook', $value);
        // return self for function chaining
        return $this;

    }

    public function uid(string $value): self {

        // creates or updates parameter and assigns value
        $this->condition('uid', $value);
        // return self for function chaining
        return $this;

    }

    public function kind(string $value): self {

        // creates or updates parameter and assigns value
        $this->condition('kind', $value);
        // return self for function chaining
        return $this;

    }

    public function member(string $value): self {

        // creates or updates parameter and assigns value
        $this->condition('hasMember', $value);
        // return self for function chaining
        return $this;

    }
    
    public function createdAfter(DateTime|DateTimeImmutable $value): self {

        // creates or updates parameter and assigns value
        $this->condition('createdAfter', $value->format(self::DATE_FORMAT_UTC));
        // return self for function chaining
        return $this;

    }

    public function createdBefore(DateTime|DateTimeImmutable $value): self {

        // creates or updates parameter and assigns value
        $this->condition('createdBefore', $value->format(self::DATE_FORMAT_UTC));
        // return self for function chaining
        return $this;

    }

    public function updatedAfter(DateTime|DateTimeImmutable $value): self {

        // creates or updates parameter and assigns value
        $this->condition('updatedAfter', $value->format(self::DATE_FORMAT_UTC));
        // return self for function chaining
        return $this;

    }

    public function updatedBefore(DateTime|DateTimeImmutable $value): self {

        // creates or updates parameter and assigns value
        $this->condition('updatedBefore', $value->format(self::DATE_FORMAT_UTC));
        // return self for function chaining
        return $this;

    }

    public function text(string $value): self {

        // creates or updates parameter and assigns value
        $this->condition('text', $value);
        // return self for function chaining
        return $this;

    }

    public function name(string $value): self {

        // creates or updates parameter and assigns value
        $this->condition('name', $value);
        // return self for function chaining
        return $this;

    }

    public function nameGiven(string $value): self {

        // creates or updates parameter and assigns value
        $this->condition('name/given', $value);
        // return self for function chaining
        return $this;

    }

    public function nameSurname(string $value): self {

        // creates or updates parameter and assigns value
        $this->condition('name/surname', $value);
        // return self for function chaining
        return $this;

    }

    public function nameSurname2(string $value): self {

        // creates or updates parameter and assigns value
        $this->condition('name/surname2', $value);
        // return self for function chaining
        return $this;

    }

    public function nameAlias(string $value): self {

        // creates or updates parameter and assigns value
        $this->condition('nickName', $value);
        // return self for function chaining
        return $this;

    }

    public function organization(string $value): self {

        // creates or updates parameter and assigns value
        $this->condition('organization', $value);
        // return self for function chaining
        return $this;

    }

    public function mail(string $value): self {

        // creates or updates parameter and assigns value
        $this->condition('email', $value);
        // return self for function chaining
        return $this;

    }

    public function phone(string $value): self {

        // creates or updates parameter and assigns value
        $this->condition('phone', $value);
        // return self for function chaining
        return $this;

    }

    public function address(string $value): self {

        // creates or updates parameter and assigns value
        $this->condition('address', $value);
        // return self for function chaining
        return $this;

    }

    public function online(string $value): self {

        // creates or updates parameter and assigns value
        $this->condition('onlineService', $value);
        // return self for function chaining
        return $this;

    }

    public function note(string $value): self {

        // creates or updates parameter and assigns value
        $this->condition('note', $value);
        // return self for function chaining
        return $this;

    }
}
