<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient\Requests;

use JmapClient\Requests\Request;

class RequestQuery extends Request {

    protected string $_method = 'query';
    protected string $_filterClass = RequestFilter::class;
    protected string $_sortClass = RequestSort::class;
    
    public function filter(): RequestFilter {
        if (!isset($this->_command['filter'])) {
            $this->_command['filter'] = new \stdClass();
        }
        return new $this->_filterClass($this->_command['filter']);
    }

    public function sort(): RequestSort {
        if (!isset($this->_command['sort'])) {
            $this->_command['sort'] = [];
        }
        return new $this->_sortClass($this->_command['sort']);
    }

    public function limitAbsolute(?int $position = null, ?int $count = null): self {
        if ($position !== null) {
            $this->_command['position'] = $position;
        }
        if ($count !== null) {
            $this->_command['limit'] = $count;
        }
        return $this;
    }

    public function limitRelative(?int $anchor = null, ?int $count = null, ?int $offset = null): self {
        if ($anchor !== null) {
            $this->_command['anchor'] = $anchor;
        }
        if ($count !== null) {
            $this->_command['limit'] = $count;
        }
        if ($offset !== null) {
            $this->_command['anchorOffset'] = $offset;
        }
        return $this;
    }
    
    public function tally(bool $value): self {
        $this->_command['calculateTotal'] = $value;
        return $this;
    }

}
