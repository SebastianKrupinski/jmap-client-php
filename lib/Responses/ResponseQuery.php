<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Responses;

class ResponseQuery extends Response
{
    public function state(): string
    {
        return (isset($this->_response[self::RESPONSE_OBJECT]['queryState'])) ? $this->_response[self::RESPONSE_OBJECT]['queryState'] : '';
    }

    public function list(): array
    {
        return (isset($this->_response[self::RESPONSE_OBJECT]['ids'])) ? $this->_response[self::RESPONSE_OBJECT]['ids'] : [];
    }

    /**
     * Zero based index of the first returned id within the complete result
     */
    public function position(): int
    {
        return (int)($this->_response[self::RESPONSE_OBJECT]['position'] ?? 0);
    }

    /**
     * Number of ids in the complete result, only present when requested with calculateTotal
     */
    public function total(): ?int
    {
        return isset($this->_response[self::RESPONSE_OBJECT]['total']) ? (int)$this->_response[self::RESPONSE_OBJECT]['total'] : null;
    }

    /**
     * Limit enforced by the server, only present when the server capped the requested limit
     */
    public function limit(): ?int
    {
        return isset($this->_response[self::RESPONSE_OBJECT]['limit']) ? (int)$this->_response[self::RESPONSE_OBJECT]['limit'] : null;
    }
}
