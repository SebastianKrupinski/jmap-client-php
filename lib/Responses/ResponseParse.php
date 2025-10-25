<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient\Responses;

use JmapClient\Responses\Response;
use JmapClient\Responses\ResponseClasses;

class ResponseParse extends Response
{

    public function __construct (array $response = []) {

        parent::__construct($response);

        // evaluate if class exists for this response object type
        $class = isset(ResponseClasses::$Parameters[$this->class()]) ? ResponseClasses::$Parameters[$this->class()] : null;
        // evaluate if class was found
        if ($class !== null) {
            // convert response objects to classes
            foreach ($this->_response[self::RESPONSE_OBJECT]['parsed'] as $key => $entry) {
                $this->_response[self::RESPONSE_OBJECT]['parsed'][$key] = new $class($entry);
            }
        }

    }

    public function state(): string {
        return (isset($this->_response[self::RESPONSE_OBJECT]['state'])) ? $this->_response[self::RESPONSE_OBJECT]['state'] : '';
    }

    public function objects(): array {
        return (isset($this->_response[self::RESPONSE_OBJECT]['parsed'])) ? $this->_response[self::RESPONSE_OBJECT]['parsed'] : [];
    }

    public function object(int $position): mixed {
        return (isset($this->_response[self::RESPONSE_OBJECT]['parsed'])) ? $this->_response[self::RESPONSE_OBJECT]['parsed'][$position] : null;
    }

}
