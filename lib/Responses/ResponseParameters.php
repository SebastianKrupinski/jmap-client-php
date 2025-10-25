<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient\Responses;

class ResponseParameters
{
    protected array $_response;

    public function __construct(array $response = []) {
        $this->_response = $response;
    }

    public function parameter(string $name): mixed {
        return isset($this->_response[$name]) ? $this->_response[$name] : null;
    }

    public function parametersRaw(): array {
        return $this->_response;
    }

}
