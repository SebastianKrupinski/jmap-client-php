<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Enums\Contacts;

/**
 * Phone number features (RFC 9553 Section 2.3.3), servers may use other values
 */
enum ContactPhoneFeature: string
{
    case Mobile = 'mobile';
    case Voice = 'voice';
    case Text = 'text';
    case Video = 'video';
    case MainNumber = 'main-number';
    case Textphone = 'textphone';
    case Fax = 'fax';
    case Pager = 'pager';
}
