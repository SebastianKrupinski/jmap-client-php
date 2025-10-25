<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient\Requests\Contacts;

use DateTimeInterface;
use JmapClient\Requests\RequestParameters;

class ContactDateStampParameters extends RequestParameters {
    
    public function __construct(&$parameters = null) {
        parent::__construct($parameters);
        $this->parameter('@type', 'Timestamp');
    }

    public function type(string $value): static {
        $this->parameter('@type', $value);
        return $this;
    }

    public function value(DateTimeInterface $value): static {
        $this->parameter('utc', $value->format(static::DATE_FORMAT_UTC));
        return $this;
    }

}
