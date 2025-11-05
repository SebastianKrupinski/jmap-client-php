<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Requests;

use JmapClient\Exceptions\InvalidParameterTypeException;
use JmapClient\Requests\Interfaces\RequestParametersInterface;
use JmapClient\Requests\Interfaces\RequestSetInterface;

/**
 * @template TParameters of RequestParametersInterface
 * @implements RequestSetInterface<TParameters>
 */
class RequestSet extends Request implements RequestSetInterface
{
    protected string $_method = 'set';
    protected string $_parametersClass = RequestParameters::class;

    public function state(string $state): static
    {
        $this->_command['ifInState'] = $state;
        return $this;
    }

    /**
     * Create a new object
     *
     * @param string $id Object identifier for creation
     * @param TParameters|null $object Optional parameters object
     *
     * @return TParameters The parameters object for method chaining
     */
    public function create(string $id, mixed $object = null): RequestParametersInterface
    {

        // get the class to use (override or default)
        $class = RequestClasses::getParameter($this->_class . '.object') ?? $this->_parametersClass;

        // validate object type if provided
        if ($object !== null && !($object instanceof $class)) {
            throw new InvalidParameterTypeException($class, $object, 'object');
        }

        // evaluate if create parameter exist and create if needed
        if (!isset($this->_command['create'][$id]) && $object === null) {
            $this->_command['create'][$id] = new \stdClass();
        } elseif ($object !== null) {
            $object->bind($this->_command['create'][$id]);
        }
        // return instance of the specific parameters class
        $instance = new $class($this->_command['create'][$id]);

        return $instance;

    }

    /**
     * Update an existing object
     *
     * @param string $id Object identifier
     * @param TParameters|null $object Optional parameters object
     *
     * @return TParameters The parameters object for method chaining
     */
    protected function update(string $id, RequestParametersInterface|null $object = null): RequestParametersInterface
    {

        // get the class to use (override or default)
        $class = RequestClasses::getParameter($this->_class . '.object') ?? $this->_parametersClass;

        // validate object type if provided
        if ($object !== null && !($object instanceof $class)) {
            throw new InvalidParameterTypeException($class, $object, 'object');
        }

        // evaluate if update parameter exist and create if needed
        if (!isset($this->_command['update'][$id]) && $object === null) {
            $this->_command['update'][$id] = new \stdClass();
        } elseif ($object !== null) {
            $object->bind($this->_command['update'][$id]);
        }
        // return instance of the specific parameters class
        $instance = new $class($this->_command['update'][$id]);

        return $instance;

    }

    /**
     * Delete an existing object
     *
     * @param string $id object identifier
     *
     * @return self
     */
    protected function delete(string $id): static
    {

        $this->_command['destroy'][] = $id;
        return $this;

    }

}
