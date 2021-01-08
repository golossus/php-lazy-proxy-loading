<?php declare(strict_types = 1);

namespace Golossus\LazyProxyLoading\Tests;

use Golossus\LazyProxyLoading\LaravelLazyLoadingTrait;
use PHPUnit\Framework\TestCase;

class LaravelLazyLoadingTraitTest extends TestCase
{
    public function testLazy()
    {
        $class = new TraitTestClass();

        $lazyClass = $class->lazy(
            Dummy::class,
            function () {
                return new Dummy();
            }
        );

        $proxy = $lazyClass[1]();

        $this->assertEquals(Dummy::class, $lazyClass[0]);
        $this->assertInstanceOf(Dummy::class, $proxy);

        $this->assertFalse($proxy->isLoaded());
        $this->assertEquals((new Dummy())->hello(), $lazyClass[1]()->hello());
        $this->assertTrue($proxy->isLoaded());
    }
}

class TraitTestClass
{
    use LaravelLazyLoadingTrait;
}