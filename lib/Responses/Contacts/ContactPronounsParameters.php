<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Responses\Contacts;

use JmapClient\Responses\ResponseParameters;

class ContactPronounsParameters extends ResponseParameters
{
    public function type(): string|null
    {
        return $this->parameter('@type');
    }

    /**
     * Get the pronouns string
     * Examples: "she/her", "they/them", "xe/xir"
     *
     * @return string|null
     */
    public function pronouns(): string|null
    {
        return $this->parameter('pronouns');
    }

    /**
     * Get the contexts for these pronouns
     * Returns array keys from contexts where value is true
     * e.g., ['work' => true, 'private' => true]
     *
     * @return array|null
     */
    public function contexts(): array|null
    {
        $value = $this->parameter('contexts');
        return $value ? array_keys((array)$value) : null;
    }

    /**
     * Get the preference for these pronouns (1-100, lower is more preferred)
     *
     * @return int|null
     */
    public function preference(): int|null
    {
        return $this->parameter('pref');
    }
}
