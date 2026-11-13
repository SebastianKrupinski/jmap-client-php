<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Enums\Contacts;

/**
 * Phonetic systems for names and addresses (RFC 9553 Section 1.5.4), servers may use other values
 */
enum ContactPhoneticSystem: string
{
    case Ipa = 'ipa';
    case Jyut = 'jyut';
    case Piny = 'piny';
}
