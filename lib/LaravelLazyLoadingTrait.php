<?php

declare(strict_types=1);

namespace Golossus\LazyProxyLoading;

use Closure;
use ReflectionException;

trait LaravelLazyLoadingTrait
{
    /**
     * @throws ReflectionException
     */
    public function lazy(string $className, Closure $classFactoryCallback): array
    {
        $proxy = ProxyClassFactory::create($className, $classFactoryCallback);

        return array(
            $className,
            function () use ($proxy) {
                return $proxy;
            },
        );
    }
}
