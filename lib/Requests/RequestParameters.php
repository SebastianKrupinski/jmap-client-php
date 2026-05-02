<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Requests;

use JmapClient\Requests\Interfaces\RequestParametersInterface;
use JmapClient\Requests\Interfaces\RequestPatchInterface;
use stdClass;

class RequestParameters implements RequestParametersInterface
{
    public const DATE_FORMAT_LOCAL = Request::DATE_FORMAT_LOCAL;
    public const DATE_FORMAT_UTC = Request::DATE_FORMAT_UTC;

    protected object $_parameters;

    public function __construct(&$parameters = null)
    {
        if ($parameters === null) {
            $this->_parameters = new stdClass();
        } else {
            $this->_parameters = & $parameters;
        }
    }

    public function bind(&$anchor): static
    {
        $anchor = $this->_parameters;
        return $this;
    }

    public function patch(): RequestPatchInterface
    {
        $flatten = function (object|array $parameters, string $prefix = '') use (&$flatten): array {
            $patch = [];

            $entries = is_object($parameters) ? get_object_vars($parameters) : $parameters;

            foreach ($entries as $name => $value) {
                $path = $prefix === '' ? self::escapePatchSegment($name) : $prefix . '/' . self::escapePatchSegment($name);

                if (is_object($value)) {
                    $entries = $flatten($value, $path);
                    if ($entries === []) {
                        $patch[$path] = new stdClass();
                        continue;
                    }

                    $patch = array_merge($patch, $entries);
                    continue;
                }

                if (is_array($value) && array_is_list($value) === false) {
                    $entries = $flatten($value, $path);
                    if ($entries === []) {
                        $patch[$path] = new stdClass();
                        continue;
                    }

                    $patch = array_merge($patch, $entries);
                    continue;
                }

                $patch[$path] = $value;
            }

            return $patch;
        };

        $patch = new RequestPatch();
        $patch->parametersRaw($flatten($this->_parameters));

        return $patch;
    }

    protected function parameter(string $name, mixed $value): static
    {
        $this->_parameters->$name = $value;
        return $this;
    }

    protected function parameterStructured(string $name, string $label, mixed $value): static
    {
        if (!isset($this->_parameters->$name) || !is_object($this->_parameters->$name)) {
            $this->_parameters->$name = new stdClass();
        }
        $this->_parameters->$name->$label = $value;

        return $this;
    }

    protected function parameterCollection(string $name, mixed $value): static
    {
        if (!isset($this->_parameters->$name) || !is_array($this->_parameters->$name)) {
            $this->_parameters->$name = [];
        }
        $this->_parameters->$name[] = $value;

        return $this;
    }

    public function parametersRaw(array $value): static
    {
        $this->_parameters = (object) $value;
        return $this;
    }

    private static function escapePatchSegment(string $segment): string
    {
        return str_replace(['~', '/'], ['~0', '~1'], $segment);
    }
}
