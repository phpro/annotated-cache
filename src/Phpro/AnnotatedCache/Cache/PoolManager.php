<?php

namespace Phpro\AnnotatedCache\Cache;

use Doctrine\Common\Collections\ArrayCollection;
use Phpro\AnnotatedCache\Exception\RuntimeException;
use Psr\Cache\CacheItemPoolInterface;

/**
 * Class PoolManager
 *
 * @package Phpro\AnnotatedCache\Cache
 */
class PoolManager implements PoolManagerInterface
{

    /**
     * @var ArrayCollection
     */
    private $pools;

    /**
     * CacheManager constructor.
     */
    public function __construct()
    {
        $this->pools = new ArrayCollection();
    }

    /**
     * @param string                 $name
     * @param CacheItemPoolInterface $pool
     */
    public function addPool($name, CacheItemPoolInterface $pool)
    {
        $this->pools->set($name, $pool);
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasPool($name)
    {
        return $this->pools->containsKey($name);
    }

    /**
     * @param string $name
     *
     * @return CacheItemPoolInterface
     * @throws RuntimeException
     */
    public function getPool($name)
    {
        if (!$this->hasPool($name)) {
            throw new RuntimeException(sprintf('Could not find cache pool with name %s.', $name));
        }

        return $this->pools->get($name);
    }
}
