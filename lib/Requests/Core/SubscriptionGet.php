<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Requests\Core;

use JmapClient\Requests\RequestGet;

class SubscriptionGet extends RequestGet
{
    protected string $_space = 'urn:ietf:params:jmap:core';
    protected string $_class = 'PushSubscription';

}
