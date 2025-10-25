<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient\Requests\Contacts;

use DateTimeInterface;
use JmapClient\Requests\RequestParameters;

class ContactParameters extends RequestParameters {

    public function __construct(&$parameters = null) {
        parent::__construct($parameters);
        $this->parameter('@type', 'Card');
        $this->parameter('version', '1.0');
    }

    /** Metadata Properties */

    public function in(string $value): static {
        $this->parameterStructured('addressbookIds', $value, true);
        return $this;
    }

    public function uid(string $value): static {
        $this->parameter('uid', $value);
        return $this;
    }

    public function type(string $value): static {
        $this->parameter('@type', $value);
        return $this;
    }

    public function version(string $value): static {
        $this->parameter('version', $value);
        return $this;
    }

    public function created(DateTimeInterface $value): static {
        $this->parameter('created', $value->format(static::DATE_FORMAT_UTC));
        return $this;
    }

    public function updated(DateTimeInterface $value): static {
        $this->parameter('updated', $value->format(static::DATE_FORMAT_UTC));
        return $this;
    }

    public function source(string $value): static {
        $this->parameter('prodId', $value);
        return $this;
    }

    public function kind(string $value): static {
        $this->parameter('kind', $value);
        return $this;
    }

    public function language(string $value): static {
        $this->parameter('language', $value);
        return $this;
    }

    public function members(string ...$values): static {
        foreach ($values as $value) {
            $this->parameterStructured('members', $value, true);
        }
        return $this;
    }

    /**
     * Add a related contact for this contact
     * The uid parameter is the unique identifier of the related contact
     * 
     * @param string $uid
     * @return ContactRelationParameters
     */
    public function relations(string $uid): ContactRelationParameters {
        if (!isset($this->_parameters->relatedTo?->$uid)) {
            $this->parameterStructured('relatedTo', $uid, new \stdClass());
        }
        return new ContactRelationParameters($this->_parameters->relatedTo->$uid);
    }

    /** Name Properties */
    
    public function name(): ContactNameParameters {
        if (!isset($this->_parameters->name)) {
            $this->parameter('name', new \stdClass());
        }
        return new ContactNameParameters($this->_parameters->name);
    }

    public function aliases(string $id): ContactAliasParameters {
        if (!isset($this->_parameters->nicknames?->$id)) {
            $this->parameterStructured('nicknames', $id, new \stdClass());
        }
        return new ContactAliasParameters($this->_parameters->nicknames->$id);
    }

    /**
     * Get the SpeakToAs information for this contact
     * Includes grammatical gender and pronouns
     * 
     * @return ContactSpeakToAsParameters
     */
    public function speakToAs(): ContactSpeakToAsParameters {
        if (!isset($this->_parameters->speakToAs)) {
            $this->parameter('speakToAs', new \stdClass());
        }
        return new ContactSpeakToAsParameters($this->_parameters->speakToAs);
    }

    /** Personal Properties */

    public function anniversaries(string $id): ContactAnniversaryParameters {
        if (!isset($this->_parameters->anniversaries?->$id)) {
            $this->parameterStructured('anniversaries', $id, new \stdClass());
        }
        return new ContactAnniversaryParameters($this->_parameters->anniversaries->$id);
    }

    /** Organization Properties */

    public function titles(string $id): ContactTitleParameters {
        if (!isset($this->_parameters->titles?->$id)) {
            $this->parameterStructured('titles', $id, new \stdClass());
        }
        return new ContactTitleParameters($this->_parameters->titles->$id);
    }

    public function organizations(string $id): ContactOrganizationParameters {
        if (!isset($this->_parameters->organizations?->$id)) {
            $this->parameterStructured('organizations', $id, new \stdClass());
        }
        return new ContactOrganizationParameters($this->_parameters->organizations->$id);
    }

    /** Communication Properties  */

    public function emails(string $id): ContactEmailParameters {
        if (!isset($this->_parameters->emails?->$id)) {
            $this->parameterStructured('emails', $id, new \stdClass());
        }
        return new ContactEmailParameters($this->_parameters->emails->$id);
    }

    public function phones(string $id): ContactPhoneParameters {
        if (!isset($this->_parameters->phones?->$id)) {
            $this->parameterStructured('phones', $id, new \stdClass());
        }
        return new ContactPhoneParameters($this->_parameters->phones->$id);
    }

    public function addresses(string $id): ContactAddressParameters {
        if (!isset($this->_parameters->addresses?->$id)) {
            $this->parameterStructured('addresses', $id, new \stdClass());
        }
        return new ContactAddressParameters($this->_parameters->addresses->$id);
    }

    /** Commentary Properties */

    public function tags(string ...$value): self {
        foreach ($value as $entry) {
            $collection[$entry] = true;
        }
        $this->parameter('keywords', (object)$collection);
        return $this;
    }

    public function notes(string $id): ContactNoteParameters {
        if (!isset($this->_parameters->notes?->$id)) {
            $this->parameterStructured('notes', $id, new \stdClass());
        }
        return new ContactNoteParameters($this->_parameters->notes->$id);
    }

    /** Resources Properties  */

    public function crypto(string $id): ContactCryptoParameters {
        if (!isset($this->_parameters->cryptoKeys?->$id)) {
            $this->parameterStructured('cryptoKeys', $id, new \stdClass());
        }
        return new ContactCryptoParameters($this->_parameters->cryptoKeys->$id);
    }

    public function directories(string $id): ContactDirectoryParameters {
        if (!isset($this->_parameters->directories?->$id)) {
            $this->parameterStructured('directories', $id, new \stdClass());
        }
        return new ContactDirectoryParameters($this->_parameters->directories->$id);
    }

    public function links(string $id): ContactLinkParameters {
        if (!isset($this->_parameters->links?->$id)) {
            $this->parameterStructured('links', $id, new \stdClass());
        }
        return new ContactLinkParameters($this->_parameters->links->$id);
    }

    public function media(string $id): ContactMediaParameters {
        if (!isset($this->_parameters->media?->$id)) {
            $this->parameterStructured('media', $id, new \stdClass());
        }
        return new ContactMediaParameters($this->_parameters->media->$id);
    }

}
