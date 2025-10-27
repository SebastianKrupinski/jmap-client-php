<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient\Session;

use JmapClient\ArrayObjectCollection;

/**
 * CapabilityCollection - Collection of JMAP capabilities
 */
final class CapabilityCollection extends ArrayObjectCollection {

    /**
     * Constructor
     * 
     * @param array $capabilities Array of capability data keyed by capability ID
     */
    public function __construct(array $capabilities = []) {
        $items = [];
        
        foreach ($capabilities as $id => $data) {
            $items[$id] = new Capability($id, $data);
        }
        
        parent::__construct($items, Capability::class);
    }

    /**
     * Get a capability by ID
     */
    public function capability(string $id): ?Capability {
        return $this->findByKey($id);
    }

    /**
     * Check if a capability is supported and not empty
     */
    public function capable(string $id): bool {
        $capability = $this->capability($id);
        return $capability !== null;
    }

}
