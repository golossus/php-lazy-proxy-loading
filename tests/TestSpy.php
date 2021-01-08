<?php declare(strict_types = 1);

namespace Golossus\LazyProxyLoading\Tests;


class TestSpy
{
    public static $method;
    public static $input;

    private static function setVars($method, $input = null)
    {
        static::$method = $method;
        static::$input = $input;
    }

    public function noReturnType()
    {
        static::setVars('noReturnType');
    }

    public function voidReturnType(): void
    {
        static::setVars('voidReturnType');
    }

    public function nullableBuiltInReturnType(): ?int
    {
        static::setVars('nullableBuiltInReturnType');

        return 1;
    }

    public function notNullableBuiltInReturnType(): bool
    {
        static::setVars('notNullableBuiltInReturnType');

        return true;
    }

    public function notNullableNotBuiltInReturnType(): Dummy
    {
        static::setVars('notNullableNotBuiltInReturnType');

        return new Dummy();
    }

    public function nullableNotBuiltInReturnType(): ?Dummy
    {
        static::setVars('nullableNotBuiltInReturnType');

        return new Dummy();
    }


    public function noTypeHintingParamType($value)
    {
        static::setVars('noTypeHintingParamType', $value);
    }


    public function nullableBuiltInParamType(?int $value)
    {
        static::setVars('nullableBuiltInParamType', $value);
    }

    public function notNullableBuiltInParamType(bool $value)
    {
        static::setVars('notNullableBuiltInParamType', $value);
    }

    public function notNullableNotBuiltInParamType(Dummy $value)
    {
        static::setVars('notNullableNotBuiltInParamType', $value);
    }

    public function nullableNotBuiltInParamType(?Dummy $value)
    {
        static::setVars('nullableNotBuiltInParamType', $value);
    }

    public function nullableDefaultParamType(?Dummy $juan = null)
    {
        static::setVars('nullableDefaultParamType', $juan);
    }

    public function notNullableDefaultParamType(int $value = 10)
    {
        static::setVars('notNullableDefaultParamType', $value);
    }

    public function paramList($value1, ?int $value2, bool $value3, Dummy $value4, ?Dummy $value5, ?Dummy $value6 = null, int $value7 = 10)
    {
        static::setVars('paramList', func_get_args());
    }

    public function withCallableParameter(callable $param){
        static::setVars('withCallableParameter', $param);
    }
    
}