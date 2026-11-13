<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Enums\Files;

/**
 * What happens when a create, update or copy would give a node the same name as a sibling
 */
enum NodeOnExists: string
{
    /** Destroy the existing node and use the name */
    case Replace = 'replace';
    /** Give the new node a different name chosen by the server */
    case Rename = 'rename';
}
