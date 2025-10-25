<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient\Requests\Contacts;

use JmapClient\Requests\RequestParameters;

class ContactNoteAuthorParameters extends RequestParameters {

    public function __construct(&$parameters = null) {
        parent::__construct($parameters);
        $this->parameter('@type', 'Author');
    }

    public function type(string $value): static {
        $this->parameter('@type', $value);
        return $this;
    }

    public function name(string $value): static {
        $this->parameter('name', $value);
        return $this;
    }

}
