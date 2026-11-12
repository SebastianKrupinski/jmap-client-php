<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Requests;

use JmapClient\Exceptions\InvalidParameterTypeException;
use JmapClient\Requests\Interfaces\RequestParametersInterface;

/**
 * Standard Foo/copy method (RFC 8620 Section 5.4)
 *
 * The account given to the constructor is the destination account,
 * the source account defaults to the same account unless set with sourceAccount()
 *
 * @template TParameters of RequestParametersInterface
 */
class RequestCopy extends Request
{
    protected string $_method = 'copy';
    protected string $_parametersClass = RequestParameters::class;

    public function __construct(?string $account = null, ?string $identifier = null, ?string $space = null, ?string $class = null, ?string $method = null)
    {
        parent::__construct($account, $identifier, $space, $class, $method);
        $this->_command['fromAccountId'] = $this->_account;
    }

    /**
     * Account to copy the objects from, defaults to the destination account
     */
    public function sourceAccount(string $account): static
    {
        $this->_command['fromAccountId'] = $account;
        return $this;
    }

    /**
     * Only copy if the state of the source account matches
     */
    public function sourceState(string $state): static
    {
        $this->_command['ifFromInState'] = $state;
        return $this;
    }

    /**
     * Only copy if the state of the destination account matches
     */
    public function destinationState(string $state): static
    {
        $this->_command['ifInState'] = $state;
        return $this;
    }

    /**
     * Destroy the originals in the source account after a successful copy
     */
    public function destroySource(bool $value): static
    {
        $this->_command['onSuccessDestroyOriginal'] = $value;
        return $this;
    }

    /**
     * Only destroy the originals if the state of the source account matches
     */
    public function destroySourceState(string $state): static
    {
        $this->_command['destroyFromIfInState'] = $state;
        return $this;
    }

    /**
     * Copy an object
     *
     * @param string $id Creation identifier for the copy
     * @param string $source Identifier of the object to copy in the source account
     * @param TParameters|null $object Optional parameters object with properties to override on the copy
     *
     * @return TParameters The parameters object for method chaining
     */
    public function copy(string $id, string $source, mixed $object = null): RequestParametersInterface
    {
        // get the class to use (override or default)
        $class = RequestClasses::getParameter($this->_class . '.object') ?? $this->_parametersClass;

        // validate object type if provided
        if ($object !== null && !($object instanceof $class)) {
            throw new InvalidParameterTypeException($class, $object, 'object');
        }

        // evaluate if copy parameter exist and create if needed
        if (!isset($this->_command['create'][$id]) && $object === null) {
            $this->_command['create'][$id] = new \stdClass();
        } elseif ($object !== null) {
            $object->bind($this->_command['create'][$id]);
        }
        $this->_command['create'][$id]->id = $source;

        return new $class($this->_command['create'][$id]);
    }
}
