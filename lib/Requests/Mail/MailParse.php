<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient\Requests\Mail;

use JmapClient\Requests\RequestParse;

class MailParse extends RequestParse {

    protected string $_space = 'urn:ietf:params:jmap:mail';
    protected string $_class = 'Email';

    public function property(string ...$value): self {

        if (!isset($this->_command['properties'])) {
            $this->_command['properties'] = [];
        }
        // creates or updates parameter and assigns value
        $this->_command['properties'] = array_merge($this->_command['properties'], $value);
        // return self for function chaining 
        return $this;

    }

    public function bodyProperty(string ...$value): self {

        if (!isset($this->_command['bodyProperties'])) {
            $this->_command['bodyProperties'] = [];
        }
        // creates or updates parameter and assigns value
        $this->_command['bodyProperties'] = array_merge($this->_command['bodyProperties'], $value);
        // return self for function chaining 
        return $this;

    }

    public function bodyText(bool $value): self {

        // creates or updates parameter and assigns value
        $this->_command['fetchTextBodyValues'] = $value;
        // return self for function chaining 
        return $this;

    }

    public function bodyHtml(bool $value): self {

        // creates or updates parameter and assigns value
        $this->_command['fetchHTMLBodyValues'] = $value;
        // return self for function chaining 
        return $this;
        
    }

    public function bodyAll(bool $value): self {

        // creates or updates parameter and assigns value
        $this->_command['fetchAllBodyValues'] = $value;
        // return self for function chaining 
        return $this;
        
    }

    public function bodyTruncate(int $value): self {

        // creates or updates parameter and assigns value
        $this->_command['maxBodyValueBytes'] = $value;
        // return self for function chaining 
        return $this;
        
    }

}
