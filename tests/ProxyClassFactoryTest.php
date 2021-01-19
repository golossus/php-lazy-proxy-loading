<?php

declare(strict_types=1);

namespace Golossus\LazyProxyLoading\Tests;

use Golossus\LazyProxyLoading\ProxyClassFactory;
use PHPUnit\Framework\TestCase;

class ProxyClassFactoryTest extends TestCase
{
    public function testCreate()
    {
        /** @var TestSpy $proxy */
        $instanceSpy = new InstanceSpy();
        $proxy = ProxyClassFactory::create(TestSpy::class, function () use ($instanceSpy) {
            return new TestSpy($instanceSpy);
        });

        $this->assertInstanceOf(TestSpy::class, $proxy);

        $proxy->noReturnType();
        $this->assertEquals('noReturnType', $instanceSpy->getMethod());
        $this->assertNull($instanceSpy->getInput());

        $proxy->voidReturnType();
        $this->assertEquals('voidReturnType', $instanceSpy->getMethod());
        $this->assertNull($instanceSpy->getInput());

        $output = $proxy->nullableBuiltInReturnType();
        $this->assertEquals('nullableBuiltInReturnType', $instanceSpy->getMethod());
        $this->assertNull($instanceSpy->getInput());
        $this->assertEquals($output, 1);

        $output = $proxy->notNullableBuiltInReturnType();
        $this->assertEquals('notNullableBuiltInReturnType', $instanceSpy->getMethod());
        $this->assertNull($instanceSpy->getInput());
        $this->assertEquals($output, true);

        $output = $proxy->notNullableNotBuiltInReturnType();
        $this->assertEquals('notNullableNotBuiltInReturnType', $instanceSpy->getMethod());
        $this->assertNull($instanceSpy->getInput());
        $this->assertEquals($output, new Dummy());

        $output = $proxy->nullableNotBuiltInReturnType();
        $this->assertEquals('nullableNotBuiltInReturnType', $instanceSpy->getMethod());
        $this->assertNull($instanceSpy->getInput());
        $this->assertEquals($output, new Dummy());

        $proxy->noTypeHintingParamType(10);
        $this->assertEquals('noTypeHintingParamType', $instanceSpy->getMethod());
        $this->assertEquals(10, $instanceSpy->getInput());

        $proxy->nullableBuiltInParamType(null);
        $this->assertEquals('nullableBuiltInParamType', $instanceSpy->getMethod());
        $this->assertNull($instanceSpy->getInput());

        $proxy->notNullableBuiltInParamType(true);
        $this->assertEquals('notNullableBuiltInParamType', $instanceSpy->getMethod());
        $this->assertTrue($instanceSpy->getInput());

        $proxy->notNullableNotBuiltInParamType(new Dummy());
        $this->assertEquals('notNullableNotBuiltInParamType', $instanceSpy->getMethod());
        $this->assertEquals(new Dummy(), $instanceSpy->getInput());

        $proxy->nullableNotBuiltInParamType(null);
        $this->assertEquals('nullableNotBuiltInParamType', $instanceSpy->getMethod());
        $this->assertNull($instanceSpy->getInput());

        $proxy->nullableDefaultParamType();
        $this->assertEquals('nullableDefaultParamType', $instanceSpy->getMethod());
        $this->assertNull($instanceSpy->getInput());

        $proxy->notNullableDefaultParamType();
        $this->assertEquals('notNullableDefaultParamType', $instanceSpy->getMethod());
        $this->assertEquals(10, $instanceSpy->getInput());

        $proxy->notNullableExternalConstantDefaultParamType();
        $this->assertEquals('notNullableExternalConstantDefaultParamType', $instanceSpy->getMethod());
        $this->assertEquals(Dummy::SOME_VALUE, $instanceSpy->getInput());

        $proxy->notNullableSelfConstantDefaultParamType();
        $this->assertEquals('notNullableSelfConstantDefaultParamType', $instanceSpy->getMethod());
        $this->assertEquals($proxy::SOME_VALUE, $instanceSpy->getInput());

        $proxy->paramList(10, null, true, new Dummy(), null, new Dummy());
        $this->assertEquals('paramList', $instanceSpy->getMethod());
        $this->assertEquals(array(
            10,
            null,
            true,
            new Dummy(),
            null,
            new Dummy(),
            10,
        ), $instanceSpy->getInput());

        $callable = function () {
            return 10;
        };
        $proxy->withCallableParameter($callable);
        $this->assertEquals('withCallableParameter', $instanceSpy->getMethod());
        $this->assertEquals($callable(), ($instanceSpy->getInput())());

        $proxy();
        $this->assertEquals('__invoke', $instanceSpy->getMethod());
        $this->assertNull($instanceSpy->getInput());

        $clonedProxy = clone $proxy;
        $this->assertEquals('__clone', $instanceSpy->getMethod());
        $this->assertNull($instanceSpy->getInput());
    }
}
