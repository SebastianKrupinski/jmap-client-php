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

use DateTime;
use DateTimeImmutable;
use JmapClient\Requests\Request;
use JmapClient\Requests\RequestParameters;

class TaskParameters extends RequestParameters
{
    public const DATE_FORMAT_LOCAL = Request::DATE_FORMAT_LOCAL;
    public const DATE_FORMAT_UTC = Request::DATE_FORMAT_UTC;

    public function __construct(&$parameters = null) {

        parent::__construct($parameters);

    }

    public function type(): string {

        return 'application/jstask+json;type=task';

    }

    /* Metadata Properties */

    public function in(string $value): self {
        
        // creates or updates parameter and assigns value
        $this->parameterStructured('taskListId', $value, true);
        // return self for function chaining
        return $this;

    }

    public function uid(string $value): self {
        
        // creates or updates parameter and assigns value
        $this->parameter('uid', $value);
        // return self for function chaining
        return $this;

    }

    public function created(DateTime|DateTimeImmutable $value): self {
        
        // creates or updates parameter and assigns value
        $this->parameter('created', $value->format(self::DATE_FORMAT_UTC));
        // return self for function chaining
        return $this;

    }

    public function updated(DateTime|DateTimeImmutable $value): self {
        
        // creates or updates parameter and assigns value
        $this->parameter('updated', $value->format(self::DATE_FORMAT_UTC));
        // return self for function chaining
        return $this;

    }

}
