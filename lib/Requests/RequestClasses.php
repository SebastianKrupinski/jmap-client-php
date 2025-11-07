<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Requests;

class RequestClasses
{
    private static array $parameters = [];

    private static array $commands = [];

    public static function listCommand(): array
    {
        return self::$commands;
    }

    public static function hasCommand(string $command): bool
    {
        return isset(self::$commands[$command]);
    }

    public static function getCommand(string $command): ?string
    {
        return self::$commands[$command] ?? null;
    }

    public static function setCommand(string $command, string $class): void
    {
        self::$commands[$command] = $class;
    }

    public static function listParameter(): array
    {
        return self::$parameters;
    }

    public static function hasParameter(string $parameter): bool
    {
        return isset(self::$parameters[$parameter]);
    }

    public static function getParameter(string $parameter): ?string
    {
        return self::$parameters[$parameter] ?? null;
    }

    public static function setParameter(string $parameter, string $class): void
    {
        self::$parameters[$parameter] = $class;
    }
}
