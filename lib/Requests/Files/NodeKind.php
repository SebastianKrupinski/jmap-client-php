<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Requests\Files;

/**
 * FileNode @type values defined by the JMAP FileNode Types registry
 */
enum NodeKind: string
{
    case File = 'File';
    case Directory = 'Directory';
    case SymLink = 'SymLink';
}
