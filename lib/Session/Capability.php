<?php
declare(strict_types=1);

/**
 * @copyright Copyright (c) 2023 Sebastian Krupinski <krupinski01@gmail.com>
 * 
 * @author Sebastian Krupinski <krupinski01@gmail.com>
 *
 * @license AGPL-3.0-or-later
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
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
