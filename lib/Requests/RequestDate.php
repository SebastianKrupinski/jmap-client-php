<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Requests;

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;

/**
 * Formats date objects as the JMAP date types
 */
final class RequestDate
{
    public const FORMAT_UTC = 'Y-m-d\TH:i:s\Z';
    public const FORMAT_LOCAL = 'Y-m-d\TH:i:s';
    public const FORMAT_OFFSET = 'Y-m-d\TH:i:sP';

    /**
     * UTCDate, the date is converted to UTC first (2026-11-12T11:00:00Z)
     */
    public static function utc(DateTimeInterface $value): string
    {
        return DateTimeImmutable::createFromInterface($value)->setTimezone(new DateTimeZone('UTC'))->format(self::FORMAT_UTC);
    }

    /**
     * LocalDateTime, the wall clock time of the date without a time zone (2026-11-12T12:00:00)
     */
    public static function local(DateTimeInterface $value): string
    {
        return $value->format(self::FORMAT_LOCAL);
    }

    /**
     * Date, the date with its UTC offset (2026-11-12T12:00:00+01:00)
     */
    public static function offset(DateTimeInterface $value): string
    {
        return $value->format(self::FORMAT_OFFSET);
    }
}
