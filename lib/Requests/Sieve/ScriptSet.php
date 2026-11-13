<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Requests\Sieve;

use JmapClient\Requests\Interfaces\RequestPatchInterface;
use JmapClient\Requests\RequestSet;

/**
 * @extends RequestSet<ScriptParameters>
 */
class ScriptSet extends RequestSet
{
    protected string $_space = 'urn:ietf:params:jmap:sieve';
    protected string $_class = 'SieveScript';
    protected string $_parametersClass = ScriptParameters::class;

    public function create(string $id, mixed $object = null): ScriptParameters
    {
        return parent::create($id, $object);
    }

    public function update(string $id, mixed $object = null): ScriptParameters
    {
        return parent::update($id, $object);
    }

    public function patch(string $id, mixed $object = null): RequestPatchInterface
    {
        return parent::patch($id, $object);
    }

    /**
     * Delete a script, an active script must be deactivated first
     */
    public function delete(string $id): static
    {
        return parent::delete($id);
    }

    /**
     * Activate a script after all changes succeed, deactivating the currently active script
     *
     * @param string $id script id, or a creation id prefixed with # to activate a script created in this request
     */
    public function activate(string $id): static
    {
        $this->_command['onSuccessActivateScript'] = $id;
        return $this;
    }

    /**
     * Deactivate the active script after all changes succeed, ignored when a script is activated
     */
    public function deactivate(bool $value = true): static
    {
        $this->_command['onSuccessDeactivateScript'] = $value;
        return $this;
    }
}
