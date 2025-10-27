<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient\Requests;

use JsonSerializable;

class RequestBundle implements JsonSerializable
{
    protected array $_requests = ['using' => ["urn:ietf:params:jmap:core"], 'methodCalls' => []];

    public function __construct (Request ...$commands) {
        // append requests to bundle
        $this->appendRequest(...$commands);
    }

    public function appendRequest(Request ...$requests): self {

        foreach ($requests as $request) {
            // check if name space exist already
            if (array_search($request->getNamespace(), $this->_requests['using']) === false) {
                $this->_requests['using'][] = $request->getNamespace();
            }
            $this->_requests['methodCalls'][] = $request;
        }

        return $this;

    }

    public function removeRequest(int $index): self {

        // evaluate if request exists in collection
        if (isset($this->_requests['methodCalls'][$index])) {
            unset($this->_requests['methodCalls'][$index]);
            $this->_requests['methodCalls'] = array_values($this->_requests['methodCalls']); 
        }
        
        return $this;

    }

    public function jsonSerialize(): array {
        return $this->_requests;
    }

}
