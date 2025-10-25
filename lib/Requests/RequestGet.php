<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient\Requests;

use JmapClient\Requests\Request;

class RequestGet extends Request {

    protected string $_method = 'get';

    public function target(string ...$id): self {

        $this->_command['ids'] = $id;
        
        return $this;

    }

    public function targetFromRequest(Request $request, string $selector): self {

        $this->_command['#ids'] = new \stdClass();
        $this->_command['#ids']->resultOf = $request->getIdentifier();
        $this->_command['#ids']->name = $request->getClass() . '/' . $request->getMethod();
        $this->_command['#ids']->path = $selector;
        
        return $this;

    }

    public function property(string ...$name): self {
        
        $this->_command['properties'] = $name;

        return $this;

    }

}
