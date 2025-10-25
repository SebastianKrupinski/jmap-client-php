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

        
        $this->condition('inAddressBook', $value);
        
        return $this;

    }

    public function uid(string $value): self {

        
        $this->condition('uid', $value);
        
        return $this;

    }

    public function kind(string $value): self {

        
        $this->condition('kind', $value);
        
        return $this;

    }

    public function member(string $value): self {

        
        $this->condition('hasMember', $value);
        
        return $this;

    }
    
    public function createdAfter(DateTime|DateTimeImmutable $value): self {

        
        $this->condition('createdAfter', $value->format(self::DATE_FORMAT_UTC));
        
        return $this;

    }

    public function createdBefore(DateTime|DateTimeImmutable $value): self {

        
        $this->condition('createdBefore', $value->format(self::DATE_FORMAT_UTC));
        
        return $this;

    }

    public function updatedAfter(DateTime|DateTimeImmutable $value): self {

        
        $this->condition('updatedAfter', $value->format(self::DATE_FORMAT_UTC));
        
        return $this;

    }

    public function updatedBefore(DateTime|DateTimeImmutable $value): self {

        
        $this->condition('updatedBefore', $value->format(self::DATE_FORMAT_UTC));
        
        return $this;

    }

    public function text(string $value): self {

        
        $this->condition('text', $value);
        
        return $this;

    }

    public function name(string $value): self {

        
        $this->condition('name', $value);
        
        return $this;

    }

    public function nameGiven(string $value): self {

        
        $this->condition('name/given', $value);
        
        return $this;

    }

    public function nameSurname(string $value): self {

        
        $this->condition('name/surname', $value);
        
        return $this;

    }

    public function nameSurname2(string $value): self {

        
        $this->condition('name/surname2', $value);
        
        return $this;

    }

    public function nameAlias(string $value): self {

        
        $this->condition('nickName', $value);
        
        return $this;

    }

    public function organization(string $value): self {

        
        $this->condition('organization', $value);
        
        return $this;

    }

    public function mail(string $value): self {

        
        $this->condition('email', $value);
        
        return $this;

    }

    public function phone(string $value): self {

        
        $this->condition('phone', $value);
        
        return $this;

    }

    public function address(string $value): self {

        
        $this->condition('address', $value);
        
        return $this;

    }

    public function online(string $value): self {

        
        $this->condition('onlineService', $value);
        
        return $this;

    }

    public function note(string $value): self {

        
        $this->condition('note', $value);
        
        return $this;

    }
}
