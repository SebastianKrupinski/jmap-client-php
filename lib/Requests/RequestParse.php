<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient\Requests;

use JmapClient\Requests\Request;

class RequestParse extends Request {

    protected string $_method = 'parse';

    public function target(string ...$id): self {

        // creates or updates parameter and assigns new value
        $this->_command['blobIds'] = $id;
        // return self for function chaining 
        return $this;

    }

    public function property(string ...$id): self {

        // creates or updates parameter and assigns new value
        $this->_command['properties'] = $id;
        // return self for function chaining 
        return $this;

    }

}
