<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient\Session;

use stdClass;

/**
 * Capability - Represents a single JMAP capability
 */
final class Capability {

    /**
     * The capability identifier (e.g., "urn:ietf:params:jmap:core")
     * 
     * @var string
     */
    protected string $_id;

    /**
     * The capability data
     * 
     * @var array|object|null
     */
    protected array|object|null $_data;

    /**
     * Constructor
     * 
     * @param string $id The capability identifier
     * @param array|object|null $data The capability data
     */
    public function __construct(string $id, array|object|null $data = null) {
        $this->_id = $id;
        $this->_data = $data;
    }

    /**
     * Get the capability identifier
     */
    public function id(): string {
        return $this->_id;
    }

    /**
     * Get the capability data
     */
    public function value(): array|object|null {
        return $this->_data;
    }

    /**
     * Check if capability data is empty
     */
    public function empty(): bool {
        if ($this->_data === null) {
            return true;
        }
        
        if (is_array($this->_data)) {
            return empty($this->_data);
        }
        
        if ($this->_data instanceof stdClass) {
            return empty((array)$this->_data);
        }
        
        return false;
    }

    /**
     * Get a property from the capability data
     */
    public function getProperty(string $property, mixed $default = null): mixed {
        if (is_array($this->_data)) {
            return $this->_data[$property] ?? $default;
        }
        
        if (is_object($this->_data)) {
            return $this->_data->$property ?? $default;
        }
        
        return $default;
    }

    /**
     * Check if a property exists in the capability data
     */
    public function hasProperty(string $property): bool {
        if (is_array($this->_data)) {
            return isset($this->_data[$property]);
        }
        
        if (is_object($this->_data)) {
            return isset($this->_data->$property);
        }
        
        return false;
    }

}
