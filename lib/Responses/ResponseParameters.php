<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Responses;

class ResponseParameters
{
    protected array $_response;

    public function __construct(array $response = [])
    {
        $this->_response = $response;
    }

    public function parameter(string $name): mixed
    {
        return isset($this->_response[$name]) ? $this->_response[$name] : null;
    }

    /**
     * Returns the keys of a map parameter as strings
     *
     * PHP turns numeric string keys like "1" into integers when decoding JSON,
     * map keys such as ids and keywords are always strings in JMAP
     *
     * @return list<string>|null null when the parameter is missing
     */
    protected function parameterKeys(string $name): array|null
    {
        $value = $this->parameter($name);
        if ($value === null) {
            return null;
        }
        $keys = [];
        foreach (array_keys((array)$value) as $key) {
            $keys[] = (string)$key;
        }
        return $keys;
    }

    public function parametersRaw(): array
    {
        return $this->_response;
    }
}
