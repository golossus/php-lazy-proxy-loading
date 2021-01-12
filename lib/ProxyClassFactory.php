<?php

declare(strict_types=1);

namespace Golossus\LazyProxyLoading;

use Closure;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionParameter;

class ProxyClassFactory
{
    /**
     * @return Loadable
     *
     * @throws ReflectionException
     */
    public static function create(string $className, Closure $classFactoryCallback)
    {
        $proxyStringClass = '$proxy = new class($className, $classFactoryCallback) extends \\'.$className.' implements \Golossus\LazyProxyLoading\Loadable {

            private $service;

            private $classFactoryCallback;

            private $className;

            private $loaded;

            public function __construct(string $className, \Closure $classFactoryCallback) {
                $this->className = $className;
                $this->closure = $classFactoryCallback;
                $this->loaded = false;
            }

            public function isLoaded(): bool
            {
                return $this->loaded;
            }

            %s
        };';

        $reflectedClass = new ReflectionClass($className);
        $publicStringMethods = self::buildMethods(
            $reflectedClass->getMethods(ReflectionMethod::IS_PUBLIC)
        );

        $proxyStringClass = sprintf($proxyStringClass, $publicStringMethods);

        $proxy = null;
        eval($proxyStringClass);

        return $proxy;
    }

    /**
     * @throws ReflectionException
     */
    private static function buildMethods(array $methods): string
    {
        $publicStringMethods = array();
        foreach ($methods as $method) {
            if (false !== strpos($method->getName(), '__')) {
                continue;
            }

            $methodName = $method->getName();
            $parameters = $method->getParameters();

            $stringParameters = array();
            $parametersWithoutType = array();
            foreach ($parameters as $parameter) {
                $type = self::buildParameterType($parameter);
                $parameterName = $parameter->getName();
                $defaultValue = self::buildParameterDefaultValue($parameter);

                $stringParameters[] = sprintf('%s $%s %s', $type, $parameterName, $defaultValue);
                $parametersWithoutType[] = sprintf('$%s', $parameterName);
            }
            $returnType = self::buildMethodReturnType($method);
            $publicStringMethods[] = sprintf(
                'public function %s(%s) %s{
                if(!$this->service){
                    $this->service = ($this->closure)();
                    $this->loaded = true;
                }
                %s $this->service->%s(%s);
            }',
                $methodName,
                implode(',', $stringParameters),
                $returnType,
                self::methodShouldReturn($returnType) ? 'return' : '',
                $methodName,
                implode(',', $parametersWithoutType)
            );
        }

        return implode("\n", $publicStringMethods);
    }

    private static function buildParameterType(ReflectionParameter $parameter): string
    {
        if (!$parameter->hasType()) {
            return '';
        }

        $type = '';
        if ($parameter->getType()->allowsNull()) {
            $type = '?';
        }

        if (!$parameter->getType()->isBuiltin()) {
            $type .= '\\';
        }

        $type .= $parameter->getType()->getName();

        return $type;
    }

    /**
     * @throws ReflectionException
     */
    private static function buildParameterDefaultValue(ReflectionParameter $parameter): string
    {
        if (!$parameter->isOptional()) {
            return '';
        }

        $defaultValue = '=';
        if ($parameter->isDefaultValueConstant()) {
            $defaultValue .= '\\'.$parameter->getDefaultValueConstantName();

            return $defaultValue;
        }

        $defaultValue .= $parameter->getDefaultValue() ?? 'null';

        return $defaultValue;
    }

    private static function buildMethodReturnType(ReflectionMethod $method): string
    {
        if (!$method->hasReturnType()) {
            return '';
        }

        $returnType = ':';
        if ($method->getReturnType()->allowsNull()) {
            $returnType .= '?';
        }

        if (!$method->getReturnType()->isBuiltin()) {
            $returnType .= '\\';
        }

        $returnType .= $method->getReturnType()->getName();

        return $returnType;
    }

    private static function methodShouldReturn(string $returnType)
    {
        return ':void' !== $returnType;
    }
}
