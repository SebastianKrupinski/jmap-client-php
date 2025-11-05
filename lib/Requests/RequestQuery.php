<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Requests;

use JmapClient\Requests\Interfaces\RequestQueryInterface;

/**
 * @template TFilter of RequestFilter
 * @template TSort of RequestSort
 * @implements RequestQueryInterface<TFilter, TSort>
 */
class RequestQuery extends Request implements RequestQueryInterface
{
    protected string $_method = 'query';
    protected string $_filterClass = RequestFilter::class;
    protected string $_sortClass = RequestSort::class;

    public function filter(): RequestFilter
    {

        if (!isset($this->_command['filter'])) {
            $this->_command['filter'] = new \stdClass();
        }

        $class = RequestClasses::getParameter($this->_class . '.filter') ?? $this->_filterClass;

        return new $class($this->_command['filter']);

    }

    public function sort(): RequestSort
    {

        if (!isset($this->_command['sort'])) {
            $this->_command['sort'] = [];
        }

        $class = RequestClasses::getParameter($this->_class . '.sort') ?? $this->_sortClass;

        return new $class($this->_command['sort']);

    }

    public function limitAbsolute(?int $position = null, ?int $count = null): static
    {

        if ($position !== null) {
            $this->_command['position'] = $position;
        }
        if ($count !== null) {
            $this->_command['limit'] = $count;
        }

        return $this;

    }

    public function limitRelative(?int $anchor = null, ?int $count = null, ?int $offset = null): static
    {

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

    public function tally(bool $value): static
    {

        $this->_command['calculateTotal'] = $value;

        return $this;

    }

}
