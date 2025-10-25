<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient\Requests;

use JmapClient\Requests\Request;

class RequestQueryChanges extends Request {

    protected string $_method = 'queryChanges';
    protected string $_filterClass = RequestFilter::class;
    protected string $_sortClass = RequestSort::class;
    
    public function filter(): RequestFilter {
        
        // evaluate if filter parameter exist and create if needed
        if (!isset($this->_command['filter'])) {
            $this->_command['filter'] = new \stdClass();
        }
        // return self for function chaining 
        return new $this->_filterClass($this->_command['filter']);

    }

    public function sort(): RequestSort {
        
        // evaluate if filter parameter exist and create if needed
        if (!isset($this->_command['sort'])) {
            $this->_command['sort'] = [];
        }
        // return self for function chaining 
        return new $this->_sortClass($this->_command['sort']);

    }

    public function state(string $value): self {

        // creates or updates parameter and assigns new value
        $this->_command['sinceQueryState'] = $value;
        // return self for function chaining 
        return $this;

    }

    public function limitRelative(int $value): self {

        // creates or updates parameter and assigns new value
        $this->_command['maxChanges'] = $value;
        // return self for function chaining 
        return $this;

    }

    public function limitAbsolute(string $value): self {

        // creates or updates parameter and assigns new value
        $this->_command['upToId'] = $value;
        // return self for function chaining 
        return $this;

    }

    public function tally(bool $value): self {

        // creates or updates parameter and assigns new value
        $this->_command['calculateTotal'] = $value;
        // return self for function chaining 
        return $this;

    }

}
