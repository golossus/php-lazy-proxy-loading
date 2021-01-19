<?php

declare(strict_types=1);

namespace Golossus\LazyProxyLoading\Tests;

class InstanceSpy
{
    private $method;
    private $input;

    /**
     * InstanceSpy constructor.
     */
    public function __construct()
    {
    }

    public function setVars($method, $input = null)
    {
        $this->method = $method;
        $this->input = $input;
    }

    /**
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return mixed
     */
    public function getInput()
    {
        return $this->input;
    }
}
