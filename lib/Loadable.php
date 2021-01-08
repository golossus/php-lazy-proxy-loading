<?php declare(strict_types=1);

namespace Golossus\LazyProxyLoading;

/**
 * Interface Bootable
 * @package Golossus\LazyProxyLoading
 */
interface Loadable
{
    /**
     * @return bool
     */
    public function isLoaded(): bool;
}