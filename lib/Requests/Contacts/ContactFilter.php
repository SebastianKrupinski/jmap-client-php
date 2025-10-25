<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient\Requests\Contacts;

use DateTime;
use DateTimeImmutable;
use JmapClient\Requests\RequestFilter;

class ContactFilter extends RequestFilter {

    public function in(string $value): self {

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
