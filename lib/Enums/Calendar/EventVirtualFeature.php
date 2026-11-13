<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Enums\Calendar;

/**
 * Features of a virtual location (RFC 8984 Section 4.2.6), servers may use other values
 */
enum EventVirtualFeature: string
{
    case Audio = 'audio';
    case Chat = 'chat';
    case Feed = 'feed';
    case Moderator = 'moderator';
    case Phone = 'phone';
    case Screen = 'screen';
    case Video = 'video';
}
