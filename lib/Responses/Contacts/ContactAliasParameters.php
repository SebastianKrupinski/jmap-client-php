<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient\Responses\Contacts;

use JmapClient\Responses\ResponseParameters;

class ContactAliasParameters extends ResponseParameters {

    public function type(): string|null {
        return $this->parameter('@type');
    }

    public function name(): string|null {
        return $this->parameter('name');
    }

    public function context(): array|null {
        $collection = $this->parameter('contexts') ?? [];
        return array_keys($collection);
    }

    public function priority(): int|null {
        return (int)$this->parameter('pref');
    }

}
