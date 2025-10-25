<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient\Requests;

class RequestSort
{
    protected array $_sort;

    public function __construct(array &$sort) {
        $this->_sort = &$sort;
    }

    public function condition(string $property, bool|null $direction = null, string|null $keyword = null, string|null $collation = null): self {

        // construct condition
        $condition = new \stdClass();
        $condition->property = $property;
        if ($direction !== null) {
            $condition->isAscending = $direction;
        }
        if ($keyword !== null) {
            $condition->keyword = $keyword;
        }
        if ($collation !== null) {
            $condition->collation = $collation;
        }
        $this->_sort[] = $condition;
        
        return $this;

    }

}
