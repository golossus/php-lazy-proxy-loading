<?php

declare(strict_types=1);

namespace Golossus\LazyProxyLoading;

/**
 * Interface Bootable.
 */
interface Loadable
{
    public function isLoaded(): bool;
}
