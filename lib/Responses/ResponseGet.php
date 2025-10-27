<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient\Responses;

use JmapClient\Responses\Response;
use JmapClient\Responses\ResponseClasses;

class ResponseGet extends Response
{

    public function __construct (array $response = []) {

        parent::__construct($response);

        // retrieve class for this response object type
        $class = ResponseClasses::getParameter($this->class());
        // evaluate if class was found
        if ($class !== null) {
            // convert response objects to classes
            foreach ($this->_response[self::RESPONSE_OBJECT]['list'] as $key => $entry) {
                $this->_response[self::RESPONSE_OBJECT]['list'][$key] = new $class($entry);
            }
        }

    }

    public function state(): string {
        return (isset($this->_response[self::RESPONSE_OBJECT]['state'])) ? $this->_response[self::RESPONSE_OBJECT]['state'] : '';
    }

    public function objects(): array {
        return (isset($this->_response[self::RESPONSE_OBJECT]['list'])) ? $this->_response[self::RESPONSE_OBJECT]['list'] : [];
    }

    public function object(int $position): mixed {
        return (isset($this->_response[self::RESPONSE_OBJECT]['list'])) ? $this->_response[self::RESPONSE_OBJECT]['list'][$position] : null;
    }

}
