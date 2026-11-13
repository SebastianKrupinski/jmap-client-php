<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Responses;

use DateTimeImmutable;
use DateTimeZone;

/**
 * Parses the JMAP date types into date objects, missing or invalid values return null
 */
final class ResponseDate
{
    /**
     * UTCDate, returned in UTC
     */
    public static function utc(mixed $value): DateTimeImmutable|null
    {
        return self::parse($value, new DateTimeZone('UTC'))?->setTimezone(new DateTimeZone('UTC'));
    }

    /**
     * LocalDateTime, returned in the given time zone, or the default time zone for floating times
     */
    public static function local(mixed $value, ?string $timeZone = null): DateTimeImmutable|null
    {
        $zone = null;
        if ($timeZone !== null && $timeZone !== '') {
            try {
                $zone = new DateTimeZone($timeZone);
            } catch (\Exception) {
                $zone = null;
            }
        }
        return self::parse($value, $zone);
    }

    /**
     * Date, returned with the UTC offset it was given with
     */
    public static function offset(mixed $value): DateTimeImmutable|null
    {
        return self::parse($value, null);
    }

    private static function parse(mixed $value, ?DateTimeZone $zone): DateTimeImmutable|null
    {
        if (!is_string($value) || $value === '') {
            return null;
        }
        try {
            return new DateTimeImmutable($value, $zone);
        } catch (\Exception) {
            return null;
        }
    }
}
