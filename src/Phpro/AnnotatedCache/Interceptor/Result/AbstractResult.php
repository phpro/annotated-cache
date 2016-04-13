<?php
declare(strict_types=1);

namespace Phpro\AnnotatedCache\Interceptor\Result;

use Phpro\AnnotatedCache\Interception\InterceptionInterface;

/**
 * Class AbstractResult
 *
 * @package Phpro\AnnotatedCache\Interceptor\Result
 */
abstract class AbstractResult implements ResultInterface
{
    /**
     * @var string
     */
    private $className = '';

    /**
     * @var string
     */
    private $method = '';

    /**
     * @var string
     */
    private $key = '';

    /**
     * @var array|string[]
     */
    private $pools = [];

    /**
     * AbstractResult constructor.
     *
     * @param InterceptionInterface $interception
     * @param string                $key
     * @param array                 $pools
     */
    public function __construct(InterceptionInterface $interception, string $key, array $pools)
    {
        $this->className = get_class($interception->getInstance());
        $this->method = $interception->getMethod();
        $this->key = $key;
        $this->pools = $pools;
    }

    /**
     * @return string
     */
    public function getClassName() : string
    {
        return $this->className;
    }

    /**
     * @return string
     */
    public function getMethod() : string
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getKey() : string
    {
        return $this->key;
    }

    /**
     * @return array|\string[]
     */
    public function getPools() : array
    {
        return $this->pools;
    }
}
