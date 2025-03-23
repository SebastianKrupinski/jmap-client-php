<?php
declare(strict_types=1);

namespace JmapClient\Responses\Contacts;

use DateTimeImmutable;
use JmapClient\Responses\ResponseParameters;

class ContactNoteParameters extends ResponseParameters {
    
    public function type(): string|null {
        return $this->parameter('@type');
    }

    public function value(): string|null {
        return $this->parameter('note');
    }

    public function created(): DateTimeImmutable|null {
        $value = $this->parameter('created');
        return ($value) ? new DateTimeImmutable($value) : null;
    }

    public function author(): string|null {
        $value = $this->parameter('name');
        return $value !== null ? new ContactNoteAuthorParameters($value) : null;
    }

}
