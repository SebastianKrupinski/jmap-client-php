<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Session;

/**
 * CoreCapability - Server limits advertised by the urn:ietf:params:jmap:core capability (RFC 8620 section 2)
 *
 * Accessors return null when the server omits a property.
 */
final class CoreCapability
{
    public const ID = 'urn:ietf:params:jmap:core';

    public function __construct(
        private Capability $capability,
    ) {
    }

    /**
     * Maximum file size, in octets, the server accepts for a single upload
     */
    public function maxSizeUpload(): ?int
    {
        return $this->integer('maxSizeUpload');
    }

    /**
     * Maximum number of concurrent requests the server accepts to the upload endpoint
     */
    public function maxConcurrentUpload(): ?int
    {
        return $this->integer('maxConcurrentUpload');
    }

    /**
     * Maximum size, in octets, the server accepts for a single request to the API endpoint
     */
    public function maxSizeRequest(): ?int
    {
        return $this->integer('maxSizeRequest');
    }

    /**
     * Maximum number of concurrent requests the server accepts to the API endpoint
     */
    public function maxConcurrentRequests(): ?int
    {
        return $this->integer('maxConcurrentRequests');
    }

    /**
     * Maximum number of method calls the server accepts in a single request to the API endpoint
     */
    public function maxCallsInRequest(): ?int
    {
        return $this->integer('maxCallsInRequest');
    }

    /**
     * Maximum number of objects the client may fetch in a single /get type method call
     */
    public function maxObjectsInGet(): ?int
    {
        return $this->integer('maxObjectsInGet');
    }

    /**
     * Maximum number of objects the client may send to create, update or destroy in a single /set type method call
     */
    public function maxObjectsInSet(): ?int
    {
        return $this->integer('maxObjectsInSet');
    }

    /**
     * Collation algorithm identifiers the server supports for sorting (RFC 4790)
     *
     * @return list<string>
     */
    public function collationAlgorithms(): array
    {
        $value = $this->capability->getProperty('collationAlgorithms', []);
        return is_array($value) ? array_values($value) : [];
    }

    private function integer(string $property): ?int
    {
        $value = $this->capability->getProperty($property);
        return is_int($value) ? $value : null;
    }
}
