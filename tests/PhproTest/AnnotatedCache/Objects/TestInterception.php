<?php

namespace PhproTest\AnnotatedCache\Objects;

use Phpro\AnnotatedCache\Interception\InterceptionInterface;

/**
 * Class TestInterception
 *
 * @package PhproTest\AnnotatedCache\Objects
 */
class TestInterception implements InterceptionInterface
{
    /**
     * @var mixed
     */
    private $instance;

    /**
     * @var string
     */
    private $method;
    /**
     * @var array
     */
    private $params;

    /**
     * TestInterception constructor.
     *
     * @param       $instance
     * @param       $method
     * @param array $params
     */
    public function __construct($instance, string $method, array $params)
    {
        $this->instance = $instance;
        $this->method = $method;
        $this->params = $params;
    }

    /**
     * @return mixed
     */
    public function getInstance()
    {
        return $this->instance;
    }

    /**
     * @return string
     */
    public function getMethod() : string
    {
        return $this->method;
    }

    /**
     * @return array
     */
    public function getParams() : array
    {
        return $this->params;
    }
}
