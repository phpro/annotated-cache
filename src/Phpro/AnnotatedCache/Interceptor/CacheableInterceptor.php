<?php

namespace Phpro\AnnotatedCache\Interceptor;

use Cache\Adapter\Common\CacheItem;
use Phpro\AnnotatedCache\Annotation\Cacheable;
use Phpro\AnnotatedCache\Annotation\CacheAnnotationInterface;
use Phpro\AnnotatedCache\Cache\PoolManager;
use Phpro\AnnotatedCache\Exception\RuntimeException;
use Phpro\AnnotatedCache\Interception\InterceptionPrefixInterface;
use Phpro\AnnotatedCache\Interception\InterceptionSuffixInterface;

/**
 * Class CacheableInterceptor
 *
 * @package Phpro\AnnotatedCache\Interceptor
 */
class CacheableInterceptor implements InterceptorInterface
{

    /**
     * @var PoolManager
     */
    private $poolManager;

    /**
     * CacheableInterceptor constructor.
     *
     * @param PoolManager $poolManager
     */
    public function __construct(PoolManager $poolManager)
    {
        $this->poolManager = $poolManager;
    }

    /**
     * @param CacheAnnotationInterface $annotation
     *
     * @return bool
     */
    public function canInterceptAnnotation(CacheAnnotationInterface $annotation)
    {
        return $annotation instanceof Cacheable;
    }

    /**
     * @param Cacheable                   $annotation
     * @param InterceptionPrefixInterface $interception
     * 
     * @return mixed
     */
    public function interceptPrefix(CacheAnnotationInterface $annotation, InterceptionPrefixInterface $interception)
    {
        $key = $this->calculateKey($annotation);
        foreach ($annotation->pools as $poolName) {
            if (!$cacheItem = $this->locateCachedItem($poolName, $key)) {
                continue;
            }

            return $cacheItem->get();
        }
        
        return null;
    }

    /**
     * @param Cacheable         $annotation
     * @param InterceptionSuffixInterface $interception
     */
    public function interceptSuffix(CacheAnnotationInterface $annotation, InterceptionSuffixInterface $interception)
    {
        $key = $this->calculateKey($annotation);

        $item = new CacheItem($key);
        $item->set($interception->getReturnValue());
        $item->setTags($annotation->tags);

        if ($annotation->ttl > 0) {
            $item->expiresAfter($annotation->ttl);
        }

        foreach ($annotation->pools as $poolName) {
            $pool = $this->poolManager->getPool('pool');
            $pool->saveDeferred($item);
        }
    }

    private function calculateKey(CacheAnnotationInterface $annotation)
    {
        return '';
    }

    /**
     * @param $poolName
     * @param $key
     *
     * @return null|\Psr\Cache\CacheItemInterface
     * @throws RuntimeException
     */
    private function locateCachedItem($poolName, $key)
    {
        $pool = $this->poolManager->getPool($poolName);
        if (!$pool->hasItem($key)) {
            return null;
        }

        $item = $pool->getItem($key);
        if (!$item->isHit()) {
            return null;
        }

        return $item;
    }
}
