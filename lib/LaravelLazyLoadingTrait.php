<?php declare(strict_types = 1);

namespace Golossus\LazyProxyLoading;

use Closure;
use ReflectionException;

trait LaravelLazyLoadingTrait
{
    /**
     * @param string  $className
     * @param Closure $classFactoryCallback
     *
     * @return array
     *
     * @throws ReflectionException
     */
    function lazy(string $className, Closure $classFactoryCallback): array
    {
       $proxy = ProxyClassFactory::create($className, $classFactoryCallback);

        return [
            $className,
            function () use ($proxy) {
                return $proxy;
            },
        ];
    }
}
