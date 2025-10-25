<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient\Requests;

use JmapClient\Exceptions\InvalidParameterTypeException;
use JmapClient\Requests\Request;

class RequestSet extends Request {

    protected string $_method = 'set';
    protected string $_parametersClass = RequestParameters::class;

    public function state(string $state): self {

        // creates or updates parameter and assigns new value
        $this->_command['ifInState'] = $state;
        // return self for function chaining 
        return $this;

    }

    public function create(string $id, ?RequestParameters $object = null): RequestParameters {
        
        // validate object type if provided
        if ($object !== null && !($object instanceof $this->_parametersClass)) {
            throw new InvalidParameterTypeException(
                $this->_parametersClass,
                $object,
                'object'
            );
        }
        
        // evaluate if create parameter exist and create if needed
        if (!isset($this->_command['create'][$id]) && $object === null) {
            $this->_command['create'][$id] = new \stdClass();
        } elseif ($object !== null) {
            $object->bind($this->_command['create'][$id]);
        }
        // return instance of the specific parameters class
        return new $this->_parametersClass($this->_command['create'][$id]);

    }

    public function update(string $id, ?RequestParameters $object = null): RequestParameters {
        
        // validate object type if provided
        if ($object !== null && !($object instanceof $this->_parametersClass)) {
            throw new InvalidParameterTypeException(
                $this->_parametersClass,
                $object,
                'object'
            );
        }
        
        // evaluate if update parameter exist and create if needed
        if (!isset($this->_command['update'][$id]) && $object === null) {
            $this->_command['update'][$id] = new \stdClass();
        } elseif ($object !== null) {
            $object->bind($this->_command['update'][$id]);
        }
        // return instance of the specific parameters class
        return new $this->_parametersClass($this->_command['update'][$id]);
    }

    public function delete(string $id): self {
        // creates or updates parameter and assigns new value
        $this->_command['destroy'][] = $id;
        // return self for function chaining 
        return $this;
    }

}
