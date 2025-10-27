<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient\Responses;

class ResponseBundle {

    protected array $_responses = [];

    public function __construct (array|object|string $responses = []) {

        if (!empty($responses)) {
            $this->jsonDeserialize($responses);
        }
        
    }

    public function jsonDeserialize(array|object|string $data): self {

        if (is_string($data)) {
            return $this->jsonDecode($data);
        }
        if (is_object($data)) {
            $data = (array)$data;
        }

        $this->_responses = $data;

        foreach ($this->_responses['methodResponses'] as $key => $entry) {
            $class = ResponseClasses::getCommand($entry[0]);
            if ($class !== null) {
                $this->_responses['methodResponses'][$key] = new $class($entry);
            }
        }

        return $this;

    }

    public function jsonDecode(string $data, int $options = 0): self {
        return $this->jsonDeserialize(json_decode($data, true, 512, JSON_THROW_ON_ERROR | $options));
    }

    public function responses(): array {
        return (isset($this->_responses['methodResponses'])) ? $this->_responses['methodResponses'] : [];
    }

    public function response(int $position): mixed {
        return (isset($this->_responses['methodResponses'])) ? $this->_responses['methodResponses'][$position] : null;
    }

    public function state(): string {
        return (isset($this->_responses['sessionState'])) ? $this->_responses['sessionState'] : '';
    }

    public function tally(): int {
        return (isset($this->_responses['methodResponses'])) ? count($this->_responses['methodResponses']) : 0;
    }

}
