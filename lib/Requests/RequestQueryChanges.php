<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Requests;

class RequestQueryChanges extends Request
{
    protected string $_method = 'queryChanges';
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

    public function state(string $value): static
    {

        $this->_command['sinceQueryState'] = $value;

        return $this;

    }

    public function limitRelative(int $value): static
    {

        $this->_command['maxChanges'] = $value;

        return $this;

    }

    public function limitAbsolute(string $value): static
    {

        $this->_command['upToId'] = $value;

        return $this;

    }

    public function tally(bool $value): static
    {


        $this->_command['calculateTotal'] = $value;

        return $this;

    }

}
