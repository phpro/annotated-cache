<?php
namespace Phpro\AnnotatedCache\Cache;

use Phpro\AnnotatedCache\Exception\RuntimeException;
use Psr\Cache\CacheItemPoolInterface;

/**
 * Class PoolManager
 *
 * @package Phpro\AnnotatedCache\Cache
 */
interface PoolManagerInterface
{
    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasPool($name);

    /**
     * @param string $name
     *
     * @return CacheItemPoolInterface
     * @throws RuntimeException
     */
    public function getPool($name);
}
