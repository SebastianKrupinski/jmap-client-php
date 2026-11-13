<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Enums\Files;

/**
 * Directory roles from the JMAP FileNode Roles registry, servers may use other roles
 */
enum NodeRole: string
{
    case Root = 'root';
    case Home = 'home';
    case Temp = 'temp';
    case Trash = 'trash';
    case Documents = 'documents';
    case Downloads = 'downloads';
    case Music = 'music';
    case Pictures = 'pictures';
    case Videos = 'videos';
}
