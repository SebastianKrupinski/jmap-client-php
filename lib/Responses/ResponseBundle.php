<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient\Responses;

class ResponseBundle {

    protected array $_response = [];

    public function __construct (array $response = []) {
        
        $this->_response = $response;
        
        foreach ($this->_response['methodResponses'] as $key => $entry) {
            if (array_key_exists($entry[0], ResponseClasses::$Commands)) {
                $class = ResponseClasses::$Commands[$entry[0]];
                $this->_response['methodResponses'][$key] = new $class($entry);
            }
        }
        
    }

    public function responses(): array {
        return (isset($this->_response['methodResponses'])) ? $this->_response['methodResponses'] : [];
    }

    public function response(int $position): mixed {
        return (isset($this->_response['methodResponses'])) ? $this->_response['methodResponses'][$position] : null;
    }

    public function state(): string {
        return (isset($this->_response['sessionState'])) ? $this->_response['sessionState'] : '';
    }

    public function tally(): int {
        return (isset($this->_response['methodResponses'])) ? count($this->_response['methodResponses']) : 0;
    }

}
