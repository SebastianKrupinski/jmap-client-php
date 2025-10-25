<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient\Requests\Blob;

use JmapClient\Requests\RequestGet;

class BlobGet extends RequestGet {

    protected string $_space = 'urn:ietf:params:jmap:Blob';
    protected string $_class = 'Blob';

    public function offset(int $value): self {
        $this->_command['offset'] = $value;
        return $this;
    }

    public function length(int $value): self {
        $this->_command['length'] = $value;
        return $this;
    }

}
