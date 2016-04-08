<?php
declare(strict_types=1);

namespace Phpro\AnnotatedCache\Interception;

/**
 * Class InterceptionInterface
 *
 * @package Phpro\AnnotatedCache\Interception
 */
interface InterceptionInterface
{

    /**
     * The wrapped instance within the proxy
     *
     * @return object
     */
    public function getInstance();

    /**
     * Name of the called method
     * @return string
     */
    public function getMethod() : string;

    /**
     * sorted array of parameters passed to the intercepted method, indexed by parameter name
     * @return array
     */
    public function getParams() : array;
}
