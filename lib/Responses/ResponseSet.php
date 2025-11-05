<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Responses;

class ResponseSet extends Response
{
    public function stateOld(): string
    {
        return $this->_response[self::RESPONSE_OBJECT]['oldState'] ?? '';
    }

    public function stateNew(): string
    {
        return $this->_response[self::RESPONSE_OBJECT]['newState'] ?? '';
    }

    public function createSuccesses(): array
    {
        return $this->_response[self::RESPONSE_OBJECT]['created'] ?? [];
    }

    public function createSuccess(string $id): ?array
    {
        return $this->_response[self::RESPONSE_OBJECT]['created'][$id] ?? null;
    }

    public function updateSuccesses(): array
    {
        return $this->_response[self::RESPONSE_OBJECT]['updated'] ?? [];
    }

    public function updateSuccess(string $id): ?array
    {
        if (isset($this->_response[self::RESPONSE_OBJECT]['updated'])) {
            return array_key_exists($id, $this->_response[self::RESPONSE_OBJECT]['updated']) ? $this->_response[self::RESPONSE_OBJECT]['updated'][$id] ?? ['id' => $id] : null;
        }
        return null;
    }

    public function deleteSuccesses(): array
    {
        return $this->_response[self::RESPONSE_OBJECT]['destroyed'] ?? [];
    }

    public function deleteSuccess(string $id): ?array
    {
        if (isset($this->_response[self::RESPONSE_OBJECT]['destroyed'])) {
            return in_array($id, $this->_response[self::RESPONSE_OBJECT]['destroyed']) ? ['id' => $id] : null;
        }
        return null;
    }

    public function failures(): bool
    {
        return isset($this->_response[self::RESPONSE_OBJECT]['notCreated']) ||
               isset($this->_response[self::RESPONSE_OBJECT]['notUpdated']) ||
               isset($this->_response[self::RESPONSE_OBJECT]['notDestroyed']);
    }

    public function createFailures(): array
    {
        return $this->_response[self::RESPONSE_OBJECT]['notCreated'] ?? [];
    }

    public function createFailure(string $id): ?array
    {
        return $this->_response[self::RESPONSE_OBJECT]['notCreated'][$id] ?? null;
    }

    public function updateFailures(): array
    {
        return $this->_response[self::RESPONSE_OBJECT]['notUpdated'] ?? [];
    }

    public function updateFailure(string $id): ?array
    {
        return $this->_response[self::RESPONSE_OBJECT]['notUpdated'][$id] ?? null;
    }

    public function deleteFailures(): array
    {
        return $this->_response[self::RESPONSE_OBJECT]['notDestroyed'] ?? [];
    }

    public function deleteFailure(string $id): ?array
    {
        return $this->_response[self::RESPONSE_OBJECT]['notDestroyed'][$id] ?? null;
    }

}
