<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Requests;

use JmapClient\Requests\Interfaces\RequestGetInterface;

/**
 * @implements RequestGetInterface
 */
class RequestGet extends Request implements RequestGetInterface
{
    protected string $_method = 'get';

    public function target(string ...$id): static
    {
        $this->_command['ids'] = $id;

        return $this;
    }

    public function targetFromRequest(Request $request, string $selector): static
    {
        $this->_command['#ids'] = new \stdClass();
        $this->_command['#ids']->resultOf = $request->getIdentifier();
        $this->_command['#ids']->name = $request->getClass() . '/' . $request->getMethod();
        $this->_command['#ids']->path = $selector;

        return $this;
    }

    public function property(string ...$name): static
    {
        $this->_command['properties'] = $name;

        return $this;
    }
}
