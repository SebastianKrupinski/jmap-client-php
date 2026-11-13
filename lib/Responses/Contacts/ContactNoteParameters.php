<?php

declare(strict_types=1);

namespace JmapClient\Responses\Contacts;

use DateTimeImmutable;
use JmapClient\Responses\ResponseDate;
use JmapClient\Responses\ResponseParameters;

class ContactNoteParameters extends ResponseParameters
{
    public function type(): string|null
    {
        return $this->parameter('@type');
    }

    public function value(): string|null
    {
        return $this->parameter('note');
    }

    public function created(): DateTimeImmutable|null
    {
        return ResponseDate::utc($this->parameter('created'));
    }

    public function author(): string|null
    {
        $value = $this->parameter('name');
        return $value !== null ? new ContactNoteAuthorParameters($value) : null;
    }
}
