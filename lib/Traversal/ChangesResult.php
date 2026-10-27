<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Traversal;

use JmapClient\Responses\ResponseChanges;

/**
 * Complete changes since a state, the first page is retrieved up front and the remaining pages on iteration
 */
class ChangesResult
{
    private bool $consumed = false;
    private ?string $state = null;

    /**
     * @param \Generator<int, ResponseChanges, mixed, string> $pages generator returning the final state
     */
    public function __construct(
        private \Generator $pages,
    ) {
    }

    /**
     * Pages of changes, can only be iterated once
     *
     * @return \Generator<int, ResponseChanges>
     *
     * @throws \LogicException when the pages were already iterated
     */
    public function pages(): \Generator
    {
        if ($this->consumed) {
            throw new \LogicException('Changes result pages can only be iterated once');
        }
        $this->consumed = true;

        foreach ($this->pages as $page) {
            yield $page;
        }
        $this->state = $this->pages->getReturn();
    }

    /**
     * State after the last page, only available once every page was iterated
     *
     * @throws \LogicException when the pages were not completely iterated
     */
    public function state(): string
    {
        if ($this->state === null) {
            throw new \LogicException('Changes result state is only available once every page was iterated');
        }

        return $this->state;
    }
}
