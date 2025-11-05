<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Requests\Tasks;

use JmapClient\Requests\RequestSet;

/**
 * @extends RequestSet<TaskListParameters>
 */
class TaskListSet extends RequestSet
{
    protected string $_space = 'urn:ietf:params:jmap:tasks';
    protected string $_class = 'TaskList';
    protected string $_parametersClass = TaskListParameters::class;

    /**
     * Create a task list
     *
     * @param string $id Task list identifier
     * @param TaskListParameters|null $object Task list parameters object
     *
     * @return TaskListParameters The task list parameters for method chaining
     */
    public function create(string $id, mixed $object = null): TaskListParameters
    {
        return parent::create($id, $object);
    }

    /**
     * Update a task list
     *
     * @param string $id Task list identifier
     * @param TaskListParameters|null $object Task list parameters object
     *
     * @return TaskListParameters The task list parameters for method chaining
     */
    public function update(string $id, mixed $object = null): TaskListParameters
    {
        return parent::update($id, $object);
    }

    /**
     * Delete a task list
     *
     * @param string $id Task list identifier
     *
     * @return self
     */
    public function delete(string $id): static
    {
        return parent::delete($id);
    }

    /**
     * Set whether to remove events when deleting the task list
     *
     * @param bool $value Whether to remove events on destruction
     *
     * @return self
     */
    public function deleteContents(bool $value): static
    {
        $this->_command['onDestroyRemoveEvents'] = $value;
        return $this;
    }

}
