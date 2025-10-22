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

use JmapClient\Requests\RequestSet;
use JmapClient\Requests\Mail\MailParameters;

class MailSet extends RequestSet {

    protected string $_space = 'urn:ietf:params:jmap:mail';
    protected string $_class = 'Email';
    protected string $_parametersClass = MailParameters::class;

    /**
     * Create an email
     * 
     * @param string $id Email identifier
     * @param MailParameters|null $object Email parameters object
     * 
     * @return MailParameters The email parameters for method chaining
     */
    public function create(string $id, $object = null): MailParameters {
        return parent::create($id, $object);
    }

    /**
     * Update an email
     * 
     * @param string $id Email identifier
     * @param MailParameters|null $object Email parameters object
     * 
     * @return MailParameters The email parameters for method chaining
     */
    public function update(string $id, $object = null): MailParameters {
        return parent::update($id, $object);
    }

    /**
     * Delete an email
     * 
     * @param string $id Email identifier
     * 
     * @return self
     */
    public function delete(string $id): self {
        return parent::delete($id);
    }

}
