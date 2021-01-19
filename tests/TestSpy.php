<?php

declare(strict_types=1);

namespace Golossus\LazyProxyLoading\Tests;

class TestSpy
{
    const SOME_VALUE = 'self-value';

    /**
     * @var InstanceSpy
     */
    private $instanceSpy;

    /**
     * TestSpy constructor.
     */
    public function __construct(InstanceSpy $instanceSpy)
    {
        $this->instanceSpy = $instanceSpy;
    }

    public function noReturnType()
    {
        $this->instanceSpy->setVars('noReturnType');
    }

    public function voidReturnType(): void
    {
        $this->instanceSpy->setVars('voidReturnType');
    }

    public function nullableBuiltInReturnType(): ?int
    {
        $this->instanceSpy->setVars('nullableBuiltInReturnType');

        return 1;
    }

    public function notNullableBuiltInReturnType(): bool
    {
        $this->instanceSpy->setVars('notNullableBuiltInReturnType');

        return true;
    }

    public function notNullableNotBuiltInReturnType(): Dummy
    {
        $this->instanceSpy->setVars('notNullableNotBuiltInReturnType');

        return new Dummy();
    }

    public function nullableNotBuiltInReturnType(): ?Dummy
    {
        $this->instanceSpy->setVars('nullableNotBuiltInReturnType');

        return new Dummy();
    }

    public function noTypeHintingParamType($value)
    {
        $this->instanceSpy->setVars('noTypeHintingParamType', $value);
    }

    public function nullableBuiltInParamType(?int $value)
    {
        $this->instanceSpy->setVars('nullableBuiltInParamType', $value);
    }

    public function notNullableBuiltInParamType(bool $value)
    {
        $this->instanceSpy->setVars('notNullableBuiltInParamType', $value);
    }

    public function notNullableNotBuiltInParamType(Dummy $value)
    {
        $this->instanceSpy->setVars('notNullableNotBuiltInParamType', $value);
    }

    public function nullableNotBuiltInParamType(?Dummy $value)
    {
        $this->instanceSpy->setVars('nullableNotBuiltInParamType', $value);
    }

    public function nullableDefaultParamType(?Dummy $juan = null)
    {
        $this->instanceSpy->setVars('nullableDefaultParamType', $juan);
    }

    public function notNullableDefaultParamType(int $value = 10)
    {
        $this->instanceSpy->setVars('notNullableDefaultParamType', $value);
    }

    public function notNullableExternalConstantDefaultParamType(string $value = Dummy::SOME_VALUE)
    {
        $this->instanceSpy->setVars('notNullableExternalConstantDefaultParamType', $value);
    }

    public function notNullableSelfConstantDefaultParamType(string $value = self::SOME_VALUE)
    {
        $this->instanceSpy->setVars('notNullableSelfConstantDefaultParamType', $value);
    }

    public function paramList($value1, ?int $value2, bool $value3, Dummy $value4, ?Dummy $value5, ?Dummy $value6 = null, int $value7 = 10)
    {
        $this->instanceSpy->setVars('paramList', \func_get_args());
    }

    public function withCallableParameter(callable $param)
    {
        $this->instanceSpy->setVars('withCallableParameter', $param);
    }

    public function __invoke()
    {
        $this->instanceSpy->setVars('__invoke');
    }

    public function __clone()
    {
        $this->instanceSpy->setVars('__clone');
    }
}
