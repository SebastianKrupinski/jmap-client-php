<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient\Requests\Tasks;

use JmapClient\Requests\RequestSet;

class TaskSet extends RequestSet {

    protected string $_space = 'urn:ietf:params:jmap:tasks';
    protected string $_class = 'Task';
    protected string $_parametersClass = TaskParameters::class;

    /**
     * Create a task
     * 
     * @param string $id Task identifier
     * @param TaskParameters|null $object Task parameters object
     * 
     * @return TaskParameters The task parameters for method chaining
     */
    public function create(string $id, $object = null): TaskParameters {
        return parent::create($id, $object);
    }

    /**
     * Update a task
     * 
     * @param string $id Task identifier
     * @param TaskParameters|null $object Task parameters object
     * 
     * @return TaskParameters The task parameters for method chaining
     */
    public function update(string $id, $object = null): TaskParameters {
        return parent::update($id, $object);
    }

    /**
     * Delete a task
     * 
     * @param string $id Task identifier
     * 
     * @return self
     */
    public function delete(string $id): self {
        return parent::delete($id);
    }

}
