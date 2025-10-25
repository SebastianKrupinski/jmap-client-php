<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient\Requests\Contacts;

use JmapClient\Requests\RequestParameters;

class ContactAnniversaryParameters extends RequestParameters {
    
    public function __construct(&$parameters = null) {
        parent::__construct($parameters);
        $this->parameter('@type', 'Anniversary');
    }

    public function type(string $value): static {
        $this->parameter('@type', $value);
        return $this;
    }

    public function dateStamp(): ContactDateStampParameters {
        if (!isset($this->_parameters->date) || $this->_parameters->date?->{'@type'} !== 'Timestamp') {
            $this->parameter('date', new \stdClass());
        }
        return new ContactDateStampParameters($this->_parameters->date);
    }

    public function datePartial(): ContactDatePartialParameters {
        if (!isset($this->_parameters->date) || $this->_parameters->date?->{'@type'} !== 'PartialDate') {
            $this->parameter('date', new \stdClass());
        }
        return new ContactDatePartialParameters($this->_parameters->date);
    }

    public function kind(string $value): static {
        $this->parameter('kind', $value);
        return $this;
    }

    public function place(): ContactAddressParameters {
        if (!isset($this->_parameters->place)) {
            $this->parameter('place', new \stdClass());
        }
        return new ContactAddressParameters($this->_parameters->place);
    }

}
