<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Requests;

use JmapClient\Exceptions\InvalidParameterTypeException;
use JmapClient\Requests\Interfaces\RequestParametersInterface;
use JmapClient\Requests\Interfaces\RequestPatchInterface;
use JmapClient\Requests\Interfaces\RequestSetInterface;

/**
 * @template TParameters of RequestParametersInterface
 * @implements RequestSetInterface<TParameters>
 */
class RequestSet extends Request implements RequestSetInterface
{
    private const UPDATE_MODE_STRUCTURED = 'structured';
    private const UPDATE_MODE_PATCH = 'patch';

    protected string $_method = 'set';
    protected string $_parametersClass = RequestParameters::class;
    protected array $_updateModes = [];

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
     * Update an existing object(s)
     *
     * @param string $id Object identifier
     * @param TParameters|null $object Optional parameters object
     *
     * @return TParameters The parameters object for method chaining
     */
    protected function update(string $id, RequestParametersInterface|null $object = null): RequestParametersInterface
    {
        if (isset($this->_updateModes[$id]) && $this->_updateModes[$id] !== self::UPDATE_MODE_STRUCTURED) {
            throw new \LogicException("Update \"$id\" is a patch object and must be accessed via patch() method");
        }
        $this->_updateModes[$id] = self::UPDATE_MODE_STRUCTURED;

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
     * Update an existing object(s) with a patch
     *
     * @param string $id Object identifier
     * @param RequestParametersInterface|RequestPatchInterface|null $object Optional structured or patch object
     *
     * @return RequestPatchInterface The patch object for method chaining
     */
    protected function patch(string $id, RequestParametersInterface|RequestPatchInterface|null $object = null): RequestPatchInterface
    {
        if (isset($this->_updateModes[$id]) && $this->_updateModes[$id] !== self::UPDATE_MODE_PATCH) {
            throw new \LogicException("Update \"$id\" is a structured object and must be accessed via update() method");
        }
        $this->_updateModes[$id] = self::UPDATE_MODE_PATCH;

        // get the class to use (override or default)
        $class = RequestClasses::getParameter($this->_class . '.object') ?? $this->_parametersClass;

        // validate object type if provided
        if ($object !== null && !($object instanceof $class) && !($object instanceof RequestPatchInterface)) {
            throw new InvalidParameterTypeException($class . '|' . RequestPatchInterface::class, $object, 'object');
        }

        if (!isset($this->_command['update'][$id]) && $object === null) {
            $this->_command['update'][$id] = new \stdClass();
        } elseif ($object instanceof RequestPatchInterface) {
            $object->bind($this->_command['update'][$id]);
        } elseif ($object instanceof RequestParametersInterface) {
            $object->patch()->bind($this->_command['update'][$id]);
        }

        return new RequestPatch($this->_command['update'][$id]);
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

    private function assertUpdateMode(string $id, string $mode): void
    {
        if (!isset($this->_updateModes[$id]) || $this->_updateModes[$id] === $mode) {
            return;
        }

        throw new \LogicException(sprintf(
            'Update "%s" is already bound in %s mode and cannot be rebound in %s mode',
            $id,
            $this->_updateModes[$id],
            $mode
        ));
    }
}
