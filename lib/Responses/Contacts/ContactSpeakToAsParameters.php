<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient\Responses\Contacts;

use JmapClient\Responses\ResponseParameters;

class ContactSpeakToAsParameters extends ResponseParameters {

    public function type(): string|null {
        return $this->parameter('@type');
    }

    /**
     * Get the grammatical gender for addressing the contact
     * Values: animate, common, feminine, inanimate, masculine, neuter
     * 
     * @return string|null
     */
    public function grammaticalGender(): string|null {
        return $this->parameter('grammaticalGender');
    }

    /**
     * Get the pronouns for this contact
     * Returns an array of ContactPronounsParameters indexed by ID
     * 
     * @return array|null
     */
    public function pronouns(): array|null {
        $collection = $this->parameter('pronouns') ?? [];
        foreach ($collection as $key => $data) {
            $collection[$key] = new ContactPronounsParameters($data);
        }
        return $collection;
    }

}
