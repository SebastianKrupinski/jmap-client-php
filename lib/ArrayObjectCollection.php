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
namespace JmapClient;

use ArrayObject;
use Countable;

/**
 * ArrayObjectCollection - A typed collection wrapper for array data with search/filter capabilities
 */
class ArrayObjectCollection extends ArrayObject implements Countable {

    /**
     * The expected class type for items in this collection
     * 
     * @var string|null
     */
    protected ?string $_itemClass = null;

    /**
     * Internal iterator position
     * 
     * @var int
     */
    private int $_position = 0;

    /**
     * Constructor
     * 
     * @param array $items Initial items for the collection
     * @param string|null $itemClass Expected class type for items
     */
    public function __construct(array $items = [], ?string $itemClass = null) {
        parent::__construct($items, ArrayObject::ARRAY_AS_PROPS);
        $this->_itemClass = $itemClass;
        $this->_position = 0;
    }

    /**
     * Find an item in the collection by key
     * 
     * @param string $key The key to search for
     * @return mixed The item if found, null otherwise
     */
    public function findByKey(string $key): mixed {
        return $this->offsetExists($key) ? $this->offsetGet($key) : null;
    }

    /**
     * Find items in the collection by matching a property value
     * 
     * @param string $property The property name to match
     * @param mixed $value The value to match
     * @return ArrayObjectCollection Filtered collection of matching items
     */
    public function findByProperty(string $property, mixed $value): ArrayObjectCollection {
        $matches = [];
        
        foreach ($this as $item) {
            if (is_object($item) && property_exists($item, $property)) {
                if ($item->$property === $value) {
                    $matches[] = $item;
                }
            } elseif (is_array($item) && isset($item[$property])) {
                if ($item[$property] === $value) {
                    $matches[] = $item;
                }
            }
        }
        
        return new self($matches, $this->_itemClass);
    }

    /**
     * Filter items in the collection using a callback function
     * 
     * @param callable $callback Function to filter items (should return true to include)
     * @return ArrayObjectCollection Filtered collection
     */
    public function filter(callable $callback): ArrayObjectCollection {
        $matches = [];
        
        foreach ($this as $key => $item) {
            if ($callback($item, $key)) {
                $matches[$key] = $item;
            }
        }
        
        return new self($matches, $this->_itemClass);
    }

    /**
     * Map items in the collection using a callback function
     * 
     * @param callable $callback Function to transform items
     * @return array Array of transformed items
     */
    public function map(callable $callback): array {
        $results = [];
        
        foreach ($this as $key => $item) {
            $results[$key] = $callback($item, $key);
        }
        
        return $results;
    }

    /**
     * Check if collection contains an item (by reference or by value for scalars)
     * 
     * @param mixed $item The item to search for
     * @param bool $strict Whether to use strict comparison
     * @return bool True if item found
     */
    public function contains(mixed $item, bool $strict = true): bool {
        foreach ($this as $value) {
            if ($strict ? $value === $item : $value == $item) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get all items as an array
     * 
     * @return array
     */
    public function toArray(): array {
        return $this->getArrayCopy();
    }

    /**
     * Get the first item in the collection
     * 
     * @return mixed The first item or null if empty
     */
    public function first(): mixed {
        if ($this->count() === 0) {
            return null;
        }
        reset($this->getArrayCopy());
        return current($this->getArrayCopy());
    }

    /**
     * Get the last item in the collection
     * 
     * @return mixed The last item or null if empty
     */
    public function last(): mixed {
        if ($this->count() === 0) {
            return null;
        }
        $array = $this->getArrayCopy();
        return end($array);
    }

    /**
     * Get all keys in the collection
     * 
     * @return array Array of keys
     */
    public function keys(): array {
        return array_keys($this->getArrayCopy());
    }

    /**
     * Get all values in the collection
     * 
     * @return array Array of values
     */
    public function values(): array {
        return array_values($this->getArrayCopy());
    }

    /**
     * Iterator implementation - Rewind to first element
     */
    public function rewind(): void {
        $this->_position = 0;
    }

    /**
     * Iterator implementation - Return current element
     */
    public function current(): mixed {
        $array = $this->getArrayCopy();
        $keys = array_keys($array);
        
        if (!isset($keys[$this->_position])) {
            return null;
        }
        
        return $array[$keys[$this->_position]];
    }

    /**
     * Iterator implementation - Return current key
     */
    public function key(): mixed {
        $keys = array_keys($this->getArrayCopy());
        
        if (!isset($keys[$this->_position])) {
            return null;
        }
        
        return $keys[$this->_position];
    }

    /**
     * Iterator implementation - Move to next element
     */
    public function next(): void {
        $this->_position++;
    }

    /**
     * Iterator implementation - Check if current position is valid
     */
    public function valid(): bool {
        $keys = array_keys($this->getArrayCopy());
        return isset($keys[$this->_position]);
    }

}
