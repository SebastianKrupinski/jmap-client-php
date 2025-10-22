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
use JmapClient\Requests\Mail\MailSubmissionParameters;

class MailSubmissionSet extends RequestSet {

    protected string $_space = 'urn:ietf:params:jmap:submission';
    protected string $_class = 'EmailSubmission';
    protected string $_parametersClass = MailSubmissionParameters::class;

    /**
     * Create a mail submission
     * 
     * @param string $id Submission identifier
     * @param MailSubmissionParameters|null $object Submission parameters object
     * 
     * @return MailSubmissionParameters The submission parameters for method chaining
     */
    public function create(string $id, $object = null): MailSubmissionParameters {
        return parent::create($id, $object);
    }

    /**
     * Update a mail submission
     * 
     * @param string $id Submission identifier
     * @param MailSubmissionParameters|null $object Submission parameters object
     * 
     * @return MailSubmissionParameters The submission parameters for method chaining
     */
    public function update(string $id, $object = null): MailSubmissionParameters {
        return parent::update($id, $object);
    }

    /**
     * Delete a mail submission
     * 
     * @param string $id Submission identifier
     * 
     * @return self
     */
    public function delete(string $id): self {
        return parent::delete($id);
    }

}
