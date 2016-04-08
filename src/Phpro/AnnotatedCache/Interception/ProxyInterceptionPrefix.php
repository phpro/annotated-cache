<?php
declare(strict_types=1);

namespace Phpro\AnnotatedCache\Interception;

/**
 * Class ProxyInterceptionPrefix
 *
 * @package Phpro\AnnotatedCache\Interception
 */
final class ProxyInterceptionPrefix implements InterceptionPrefixInterface
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
    public function __construct($instance, $method, $params)
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
