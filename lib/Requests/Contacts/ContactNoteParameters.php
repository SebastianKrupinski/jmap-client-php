<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Requests\Contacts;

use DateTimeInterface;
use JmapClient\Requests\RequestParameters;

class ContactNoteParameters extends RequestParameters
{
    public function __construct(&$parameters = null)
    {
        parent::__construct($parameters);
        $this->parameter('@type', 'Note');
    }

    public function type(string $value): static
    {
        $this->parameter('@type', $value);
        return $this;
    }

    public function contents(string $value): static
    {
        $this->parameter('note', $value);
        return $this;
    }

    public function created(DateTimeInterface $value): static
    {
        $this->parameter('created', $value->format(static::DATE_FORMAT_UTC));
        return $this;
    }

    public function author(): ContactNoteAuthorParameters
    {
        if (!isset($this->_parameters->author)) {
            $this->parameter('author', new \stdClass());
        }
        return new ContactNoteAuthorParameters($this->_parameters->author);
    }

}
