<?php declare(strict_types = 1);

namespace Golossus\LazyProxyLoading\Tests;

use Golossus\LazyProxyLoading\ProxyClassFactory;
use PHPUnit\Framework\TestCase;

class ProxyClassFactoryTest extends TestCase
{
    public function testCreate()
    {
        /** @var TestSpy $proxy */
        $proxy = ProxyClassFactory::create(TestSpy::class, function () {
            return new TestSpy();
        });

        $this->assertInstanceOf(TestSpy::class, $proxy);

        $proxy->noReturnType();
        $this->assertEquals('noReturnType', $proxy::$method);
        $this->assertEquals(null, $proxy::$input);

        $proxy->voidReturnType();
        $this->assertEquals('voidReturnType', $proxy::$method);
        $this->assertEquals(null, $proxy::$input);

        $output = $proxy->nullableBuiltInReturnType();
        $this->assertEquals('nullableBuiltInReturnType', $proxy::$method);
        $this->assertEquals(null, $proxy::$input);
        $this->assertEquals($output, 1);

        $output = $proxy->notNullableBuiltInReturnType();
        $this->assertEquals('notNullableBuiltInReturnType', $proxy::$method);
        $this->assertEquals(null, $proxy::$input);
        $this->assertEquals($output, true);

        $output = $proxy->notNullableNotBuiltInReturnType();
        $this->assertEquals('notNullableNotBuiltInReturnType', $proxy::$method);
        $this->assertEquals(null, $proxy::$input);
        $this->assertEquals($output, new Dummy());

        $output = $proxy->nullableNotBuiltInReturnType();
        $this->assertEquals('nullableNotBuiltInReturnType', $proxy::$method);
        $this->assertEquals(null, $proxy::$input);
        $this->assertEquals($output, new Dummy());


        $proxy->noTypeHintingParamType(10);
        $this->assertEquals('noTypeHintingParamType', $proxy::$method);
        $this->assertEquals(10, $proxy::$input);

        $proxy->nullableBuiltInParamType(null);
        $this->assertEquals('nullableBuiltInParamType', $proxy::$method);
        $this->assertEquals(null, $proxy::$input);

        $proxy->notNullableBuiltInParamType(true);
        $this->assertEquals('notNullableBuiltInParamType', $proxy::$method);
        $this->assertEquals(true, $proxy::$input);

        $proxy->notNullableNotBuiltInParamType(new Dummy());
        $this->assertEquals('notNullableNotBuiltInParamType', $proxy::$method);
        $this->assertEquals(new Dummy(), $proxy::$input);

        $proxy->nullableNotBuiltInParamType(null);
        $this->assertEquals('nullableNotBuiltInParamType', $proxy::$method);
        $this->assertEquals(null, $proxy::$input);

        $proxy->nullableDefaultParamType();
        $this->assertEquals('nullableDefaultParamType', $proxy::$method);
        $this->assertEquals(null, $proxy::$input);

        $proxy->notNullableDefaultParamType();
        $this->assertEquals('notNullableDefaultParamType', $proxy::$method);
        $this->assertEquals(10, $proxy::$input);

        $proxy->paramList(10, null, true, new Dummy(), null, new Dummy());
        $this->assertEquals('paramList', $proxy::$method);
        $this->assertEquals([
            10,
            null,
            true,
            new Dummy(),
            null,
            new Dummy(),
            10,
        ], $proxy::$input);

        $callable = function () {
            return 10;
        };
        $proxy->withCallableParameter($callable);
        $this->assertEquals('withCallableParameter', $proxy::$method);
        $this->assertEquals($callable(), ($proxy::$input)());

    }
}
