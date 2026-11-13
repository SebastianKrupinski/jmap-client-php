<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Enums\Contacts;

/**
 * Grammatical gender to use when addressing the contact (RFC 9553 Section 2.2.4), servers may use other values
 */
enum ContactGrammaticalGender: string
{
    case Animate = 'animate';
    case Common = 'common';
    case Feminine = 'feminine';
    case Inanimate = 'inanimate';
    case Masculine = 'masculine';
    case Neuter = 'neuter';
}
