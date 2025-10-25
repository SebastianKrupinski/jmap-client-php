<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient\Responses\Calendar;

use JmapClient\Responses\ResponseParameters;

class EventVirtualLocationParameters extends ResponseParameters {
    
    public function label(): string|null {
        return $this->parameter('name');
    }

    public function description(): string|null {
        return $this->parameter('description');
    }

    public function location(): string|null {
        return $this->parameter('uri');
    }

    public function attributes(): array|null {
        return $this->parameter('features');
    }
    
}
