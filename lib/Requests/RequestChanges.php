<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient\Requests;

use JmapClient\Requests\Request;

class RequestChanges extends Request {
    
    protected string $_method = 'changes';

    public function state(string $value): self {

        $this->_command['sinceState'] = $value;

        return $this;

    }

    public function limitRelative(int $value): self {

        $this->_command['maxChanges'] = $value;
        
        return $this;

    }

}
