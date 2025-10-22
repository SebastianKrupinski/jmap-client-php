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
namespace JmapClient\Exceptions;

class InvalidParameterTypeException extends \InvalidArgumentException
{
    /**
     * Create exception for invalid parameter type
     *
     * @param string $expectedType The expected parameter type/class
     * @param object|string|null $actualValue The actual value/object received
     * @param string $parameterName Name of the parameter (for context)
     * @param int $code Exception code
     * @param \Throwable|null $previous Previous exception for chaining
     */
    public function __construct(
        private string $expectedType,
        private mixed $actualValue,
        private string $parameterName = 'parameter',
        int $code = 0,
        \Throwable $previous = null
    ) {
        $actualType = is_object($actualValue) ? get_class($actualValue) : gettype($actualValue);
        
        $message = sprintf(
            'Invalid %s type: expected instance of %s, got %s',
            $this->parameterName,
            $this->expectedType,
            $actualType
        );

        parent::__construct($message, $code, $previous);
    }

    /**
     * Get the expected type
     */
    public function getExpectedType(): string
    {
        return $this->expectedType;
    }

    /**
     * Get the actual value that was passed
     */
    public function getActualValue(): mixed
    {
        return $this->actualValue;
    }

    /**
     * Get the parameter name
     */
    public function getParameterName(): string
    {
        return $this->parameterName;
    }

    /**
     * Check if actual value is an object
     */
    public function isActualValueObject(): bool
    {
        return is_object($this->actualValue);
    }

    /**
     * Get the actual type name
     */
    public function getActualType(): string
    {
        return is_object($this->actualValue) ? get_class($this->actualValue) : gettype($this->actualValue);
    }
}
