<?php

namespace Phpro\AnnotatedCache\Interception;

use Phpro\AnnotatedCache\Collection\AnnotationCollection;

/**
 * Class ProxyInterceptionPrefix
 *
 * @package Phpro\AnnotatedCache\Interception
 */
class ProxyInterceptionPrefix implements InterceptionPrefixInterface
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
    private $params = [];

    /**
     * ProxyInterceptionPrefix constructor.
     */
    public function __construct( $instance, $method, $params)
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
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }
}
