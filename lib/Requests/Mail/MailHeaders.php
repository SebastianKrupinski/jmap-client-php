<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Requests\Mail;

/**
 * Keeps a header field represented by a single property (RFC 8621 Email/set).
 *
 * Using classes define HEADER_PROPERTIES, mapping lower case header names
 * to the parsed properties that represent them.
 */
trait MailHeaders
{
    /**
     * Sets a header field in raw form, replacing any other property that represents it
     */
    public function header(string $name, string $value): static
    {
        return $this->headerAdd($name, $value);
    }

    /**
     * Sets multiple header fields in raw form
     *
     * @param array<string,string> $headers header name => raw value
     */
    public function headers(array $headers): static
    {
        foreach ($headers as $name => $value) {
            $this->headerAdd($name, $value);
        }
        return $this;
    }

    /**
     * Sets a header field in raw form, removing the header and parsed properties that represent the same field
     */
    protected function headerAdd(string $name, string $value): static
    {
        $this->headerRemove($name);
        foreach (static::HEADER_PROPERTIES[strtolower($name)] ?? [] as $property) {
            unset($this->_parameters->$property);
        }
        $this->_parameters->{sprintf('header:%s:asRaw', $name)} = $value;
        return $this;
    }

    /**
     * Removes all header:<name> properties for a header field, in any form or case
     */
    protected function headerRemove(string $name): static
    {
        $name = strtolower($name);
        foreach (array_keys(get_object_vars($this->_parameters)) as $property) {
            $segments = explode(':', (string)$property);
            if ($segments[0] === 'header' && isset($segments[1]) && strtolower($segments[1]) === $name) {
                unset($this->_parameters->$property);
            }
        }
        return $this;
    }
}
