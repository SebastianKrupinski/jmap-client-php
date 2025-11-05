<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Responses\Contacts;

use JmapClient\Responses\ResponseParameters;

class ContactRelationParameters extends ResponseParameters
{
    public function type(): string|null
    {
        return $this->parameter('@type');
    }

    /**
     * Get the relationship types for this related contact
     * Returns an associative array where keys are relation types and values are true
     * e.g., ['friend' => true, 'colleague' => true]
     *
     * @return array|null
     */
    public function relations(): array|null
    {
        $value = $this->parameter('relation');
        return $value ? array_keys((array)$value) : null;
    }

}
