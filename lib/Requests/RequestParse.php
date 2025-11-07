<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Requests;

class RequestParse extends Request
{
    protected string $_method = 'parse';

    public function target(string ...$id): static
    {
        $this->_command['blobIds'] = $id;

        return $this;
    }

    public function property(string ...$name): static
    {
        $this->_command['properties'] = $name;

        return $this;
    }
}
