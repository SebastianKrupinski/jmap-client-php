<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Responses\Quota;

use JmapClient\Responses\ResponseChanges;

class QuotaChanges extends ResponseChanges
{
    public function updatedProperties(): array|null
    {
        return $this->_response[self::RESPONSE_OBJECT]['updatedProperties'] ?? null;
    }
}
