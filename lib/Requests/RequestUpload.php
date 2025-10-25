<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient\Requests;

use JmapClient\Requests\Request;

class RequestUpload extends Request {

    protected string $_method = 'upload';
    
    public function create(string $id, ?RequestParameters $object = null): RequestParameters {
        
        // evaluate if create parameter exist and create if needed
        if (!isset($this->_command['create'][$id]) && $object === null) {
            $this->_command['create'][$id] = new \stdClass();
        } elseif ($object !== null) {
            $object->bind($this->_command['create'][$id]);
        }
        // return self for function chaining 
        return new RequestParameters($this->_command['create'][$id]);

    }

}
