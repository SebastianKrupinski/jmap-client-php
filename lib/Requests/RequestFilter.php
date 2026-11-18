<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Requests;

class RequestFilter
{
    protected object $_filter;

    public function __construct(object &$filter)
    {
        $this->_filter = &$filter;
    }

    public function condition(string $property, mixed $value): static
    {
        $this->_filter->$property = $value;

        return $this;
    }

    /**
     * A fresh filter of the same kind, attached to no request
     *
     * Build each condition of an operator in one, then join them with all(), any() or none().
     */
    public function fresh(): static
    {
        $condition = new \stdClass();

        return new static($condition);
    }

    /**
     * Make this filter match when every one of the filters does (the AND operator of RFC 8620 §5.5)
     */
    public function all(RequestFilter ...$filters): static
    {
        return $this->join('AND', $filters);
    }

    /**
     * Make this filter match when at least one of the filters does (the OR operator of RFC 8620 §5.5)
     */
    public function any(RequestFilter ...$filters): static
    {
        return $this->join('OR', $filters);
    }

    /**
     * Make this filter match when none of the filters does (the NOT operator of RFC 8620 §5.5)
     */
    public function none(RequestFilter ...$filters): static
    {
        return $this->join('NOT', $filters);
    }

    /**
     * Replace whatever this filter held with an operator over the filters
     *
     * Filters may themselves be operators, so conditions nest to any depth; to keep what
     * this filter held, pass it among the others.
     *
     * @param list<RequestFilter> $filters
     */
    private function join(string $operator, array $filters): static
    {
        if ($filters === []) {
            throw new \InvalidArgumentException('A filter operator needs at least one filter');
        }
        // taken before this filter is replaced, so it may be among them
        $conditions = array_map(static fn (RequestFilter $filter) => $filter->_filter, $filters);
        $this->_filter = (object)['operator' => $operator, 'conditions' => $conditions];

        return $this;
    }
}
