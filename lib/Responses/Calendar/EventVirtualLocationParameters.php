<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Responses\Calendar;

use JmapClient\Enums\Calendar\EventVirtualFeature;
use JmapClient\Responses\ResponseParameters;

class EventVirtualLocationParameters extends ResponseParameters
{
    public function label(): string|null
    {
        return $this->parameter('name');
    }

    public function description(): string|null
    {
        return $this->parameter('description');
    }

    public function location(): string|null
    {
        return $this->parameter('uri');
    }

    /**
     * @return array<int,EventVirtualFeature|string>|null features, a feature this library does not know is returned as a string
     */
    public function attributes(): array|null
    {
        $features = $this->parameter('features');
        if ($features === null) {
            return null;
        }
        $features = array_keys(array_filter((array) $features));
        return array_map(fn ($feature) => EventVirtualFeature::tryFrom((string) $feature) ?? (string) $feature, $features);
    }
}
