<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient\Responses\Contacts;

use DateTimeImmutable;
use JmapClient\Responses\ResponseParameters;

class ContactParameters extends ResponseParameters {

    /* Metadata Properties */

    public function in(): array|null {
        $value = $this->parameter('addressbookIds');
        if ($value !== null) {
            return array_keys($value);
        }
        return null;
    }
    
    public function id(): string|null {
        return $this->parameter('id');
    }

    public function uid(): string|null {
        return $this->parameter('uid');
    }

    public function type(): string|null {
        return $this->parameter('@type');
    }

    public function version(): string|null {
        return $this->parameter('version');
    }

    public function created(): DateTimeImmutable|null {
        $value = $this->parameter('created');
        return ($value) ? new DateTimeImmutable($value) : null;
    }

    public function updated(): DateTimeImmutable|null {
        $value = $this->parameter('updated');
        return ($value) ? new DateTimeImmutable($value) : null;
    }

    public function source(): string|null {
        return $this->parameter('prodId');
    }

    public function kind(): string|null {
        return $this->parameter('kind') ?? 'individual';
    }

    public function language(): string|null {
        return $this->parameter('language');
    }

    public function members(): array|null {
        return $this->parameter('members');
    }
    
    public function relations(): array|null {
        $collection = $this->parameter('relatedTo') ?? [];
        foreach ($collection as $key => $data) {
            $collection[$key] = new ContactRelationParameters($data);
        }
        return $collection;
    }

    /** Name Properties */

    public function name(): ContactNameParameters|null {
        $value = $this->parameter('name');
        if ($value !== null) {
            return new ContactNameParameters($value);
        }
        return null;
    }

    public function aliases(): array|null {
        $collection = $this->parameter('nicknames') ?? [];
        foreach ($collection as $key => $data) {
            $collection[$key] = new ContactAliasParameters($data);
        }
        return $collection;
    }

    /**
     * Get the SpeakToAs information for addressing this contact
     * Includes grammatical gender and pronouns
     * 
     * @return ContactSpeakToAsParameters|null
     */
    public function speakToAs(): ContactSpeakToAsParameters|null {
        $value = $this->parameter('speakToAs');
        if ($value !== null) {
            return new ContactSpeakToAsParameters($value);
        }
        return null;
    }

    /** Personal Properties */
    
    public function anniversaries(): array|null {
        $collection = $this->parameter('anniversaries') ?? [];
        foreach ($collection as $key => $data) {
            $collection[$key] = new ContactAnniversaryParameters($data);
        }
        return $collection;
    }

    /** Organization Properties */

    public function titles(): array|null {
        $collection = $this->parameter('titles') ?? [];
        foreach ($collection as $key => $data) {
            $collection[$key] = new ContactTitleParameters($data);
        }
        return $collection;
    }

    public function organizations(): array|null {
        $collection = $this->parameter('organizations') ?? [];
        foreach ($collection as $key => $data) {
            $collection[$key] = new ContactOrganizationParameters($data);
        }
        return $collection;
    }

    /** Communication Properties */

    public function emails(): array|null {
        $collection = $this->parameter('emails') ?? [];
        foreach ($collection as $key => $data) {
            $collection[$key] = new ContactEmailParameters($data);
        }
        return $collection;
    }

    public function phones(): array|null {
        $collection = $this->parameter('phones') ?? [];
        foreach ($collection as $key => $data) {
            $collection[$key] = new ContactPhoneParameters($data);
        }
        return $collection;
    }

    public function addresses(): array|null {
        $collection = $this->parameter('addresses') ?? [];
        foreach ($collection as $key => $data) {
            $collection[$key] = new ContactAddressParameters($data);
        }
        return $collection;
    }

    /** Commentary Properties */

    public function tags(): array|null {
        $value = $this->parameter('keywords');
        return $value ? array_keys((array)$value) : null;
    }

    public function notes(): array|null {
        $collection = $this->parameter('notes') ?? [];
        foreach ($collection as $key => $data) {
            $collection[$key] = new ContactNoteParameters($data);
        }
        return $collection;
    }

    /** Resources Properties */

    public function crypto(): array|null {
        $collection = $this->parameter('cryptoKeys') ?? [];
        foreach ($collection as $key => $data) {
            $collection[$key] = new ContactCryptoParameters($data);
        }
        return $collection;
    }

    public function directories(): array|null {
        $collection = $this->parameter('directories') ?? [];
        foreach ($collection as $key => $data) {
            $collection[$key] = new ContactDirectoryParameters($data);
        }
        return $collection;
    }

    public function links(): array|null {
        $collection = $this->parameter('links') ?? [];
        foreach ($collection as $key => $data) {
            $collection[$key] = new ContactLinkParameters($data);
        }
        return $collection;
    }

    public function media(): array|null {
        $collection = $this->parameter('media') ?? [];
        foreach ($collection as $key => $data) {
            $collection[$key] = new ContactMediaParameters($data);
        }
        return $collection;
    }

}
