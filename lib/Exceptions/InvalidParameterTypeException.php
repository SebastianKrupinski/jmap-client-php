<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
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
