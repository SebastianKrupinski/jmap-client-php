<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Requests\Core;

use JmapClient\Requests\RequestSet;

/**
 * @extends RequestSet<SubscriptionParameters>
 */
class SubscriptionSet extends RequestSet
{
    protected string $_space = 'urn:ietf:params:jmap:core';
    protected string $_class = 'PushSubscription';
    protected string $_parametersClass = SubscriptionParameters::class;

    /**
     * Create a push subscription
     *
     * @param string $id Subscription identifier
     * @param SubscriptionParameters|null $object Subscription parameters object
     * @return SubscriptionParameters The subscription parameters for method chaining
     */
    public function create(string $id, mixed $object = null): SubscriptionParameters
    {
        return parent::create($id, $object);
    }
}
