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
namespace JmapClient\Requests\Mail;

use JmapClient\Requests\RequestParameters;

class MailSubmissionParameters extends RequestParameters
{
    public function __construct(&$request, $action, $id) {

        parent::__construct($request, $action, $id);

    }

    public function identity(string $value): self {
        
        // creates or updates parameter and assigns value
        $this->parameter('identityId', $value);
        // return self for function chaining
        return $this;

    }

    public function message(string $value): self {
        
        // creates or updates parameter and assigns value
        $this->parameter('emailId', $value);
        // return self for function chaining
        return $this;

    }

    public function from(string $value): self {
        
        // creates or updates parameter and assigns value
        $this->parameterStructured('envelope', 'mailFrom', (object) ['email' => $value]);
        // return self for function chaining
        return $this;

    }

    public function to(array $value): self {
        
        // creates or updates parameter and assigns value
        $recipients = [];
        foreach ($value as $entry) {
            $recipients[] = ((object) ['email' => $entry]);
        }
        $this->parameterStructured('envelope', 'rcptTo', $recipients);
        // return self for function chaining
        return $this;

    }

    public function completionUpdate(string $id, array $actions) {

        // creates or updates parameter and assigns value
        $this->parameterStructured('onSuccessUpdateEmail', $id, $actions);
        // return self for function chaining
        return $this;

    }

    public function completionDestroy(string $id, array $actions) {
        
        // creates or updates parameter and assigns value
        $this->parameterStructured('onSuccessDestroyEmail', $id, $actions);
        // return self for function chaining
        return $this;

    }

}
