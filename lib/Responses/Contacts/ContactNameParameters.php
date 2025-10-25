<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient\Responses\Contacts;

use JmapClient\Responses\ResponseParameters;

class ContactNameParameters extends ResponseParameters {

    public function type(): string|null {
        return $this->parameter('@type');
    }

    public function full(): string|null {
        return $this->parameter('full');
    }

    public function components(): array {
        $collection = $this->parameter('components') ?? [];
        foreach ($collection as $key => $data) {
            $collection[$key] = new ContactComponentParameters($data);
        }

        return $collection;
    }

    public function separator(): string|null {
        return $this->parameter('defaultSeparator');
    }

    public function ordered(): bool|null {
        return $this->parameter('isOrdered');
    }

    public function sorting(): array|null {
        return $this->parameter('sortAs');
    }

}
