<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Requests;

use JmapClient\Requests\Interfaces\RequestParametersInterface;
use JmapClient\Requests\Interfaces\RequestUploadInterface;

/**
 * @template TParameters of RequestParametersInterface
 * @implements RequestUploadInterface<TParameters>
 */
class RequestUpload extends Request implements RequestUploadInterface
{
    protected string $_method = 'upload';
    protected string $_parametersClass = RequestParameters::class;

    /**
     * Create a new upload object
     *
     * @param string $id Object identifier for creation
     * @param TParameters|null $object Optional parameters object
     *
     * @return TParameters The parameters object for method chaining
     */
    public function create(string $id, mixed $object = null): RequestParametersInterface
    {
        // get the class to use (override or default)
        $class = $this->_parametersClass;

        // evaluate if create parameter exist and create if needed
        if (!isset($this->_command['create'][$id]) && $object === null) {
            $this->_command['create'][$id] = new \stdClass();
        } elseif ($object !== null) {
            $object->bind($this->_command['create'][$id]);
        }

        return new $class($this->_command['create'][$id]);
    }
}
