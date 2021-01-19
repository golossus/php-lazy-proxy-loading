<?php

declare(strict_types=1);

namespace Golossus\LazyProxyLoading\Tests;

class Dummy
{
    const SOME_VALUE = 'dummy-value';

    public function hello(): string
    {
        return 'hello';
    }
}
