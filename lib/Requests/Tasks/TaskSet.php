<?php
declare(strict_types=1);

/**
* @copyright Copyright (c) 2023 Sebastian Krupinski <krupinski01@gmail.com>
*
* @author Sebastian Krupinski <krupinski01@gmail.com>
*
* @license AGPL-3.0-or-later
*
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU Affero General Public License as
* published by the Free Software Foundation, either version 3 of the
* License, or (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU Affero General Public License for more details.
*
* You should have received a copy of the GNU Affero General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
*
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
