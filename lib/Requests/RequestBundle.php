<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient\Requests;

class RequestBundle
{
    protected array $_requests = ['using' => [], 'methodCalls' => []];

    public function __construct (array $spaces = [], array $requests = []) {
        $this->_requests['using'] = $spaces;
        $this->_requests['methodCalls'] = $requests;
    }

    public function appendRequest(string $space, object $request): self {

        // check if name space exist already
        if (array_search($space, $this->_requests['using']) === false) {
            $this->_requests['using'][] = $space;
        }
        // append request to collection
        $this->_requests['methodCalls'][] = $request;

        return $this;

    }

    public function removeRequest(int $index): self {

        // evaluate if request exists in collection
        if (isset($this->_requests['methodCalls'][$index])) {
            // remove request from collection
            unset($this->_requests['methodCalls'][$index]);
            // Re-index collection
            $this->_requests['methodCalls'] = array_values($this->_requests['methodCalls']); 
        }
        
        return $this;

    }

    public function phrase(): string {

        return json_encode($this->_requests, JSON_UNESCAPED_SLASHES);

    }

}
