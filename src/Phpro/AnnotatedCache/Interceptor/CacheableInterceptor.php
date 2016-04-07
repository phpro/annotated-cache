<?php

namespace Phpro\AnnotatedCache\Interceptor;

use Cache\Adapter\Common\CacheItem;
use Phpro\AnnotatedCache\Annotation\Cacheable;
use Phpro\AnnotatedCache\Annotation\CacheAnnotationInterface;
use Phpro\AnnotatedCache\Cache\PoolManager;
use Phpro\AnnotatedCache\Exception\RuntimeException;
use Phpro\AnnotatedCache\Interception\InterceptionPrefixInterface;
use Phpro\AnnotatedCache\Interception\InterceptionSuffixInterface;
use Phpro\AnnotatedCache\KeyGenerator\KeyGeneratorInterface;

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
     * @var KeyGeneratorInterface
     */
    private $keyGenerator;

    /**
     * CacheableInterceptor constructor.
     *
     * @param PoolManager           $poolManager
     * @param KeyGeneratorInterface $keyGenerator
     */
    public function __construct(PoolManager $poolManager, KeyGeneratorInterface $keyGenerator)
    {
        $this->poolManager = $poolManager;
        $this->keyGenerator = $keyGenerator;
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
        $key = $this->calculateKey($annotation, $interception);
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
        $key = $this->calculateKey($annotation, $interception);

        $item = new CacheItem($key);
        $item->set($interception->getReturnValue());
        $item->setTags($annotation->tags);

        if ($annotation->ttl > 0) {
            $item->expiresAfter($annotation->ttl);
        }

        foreach ($annotation->pools as $poolName) {
            $pool = $this->poolManager->getPool($poolName);
            $pool->saveDeferred($item);
        }
    }

    /**
     * @param Cacheable    $annotation
     * @param InterceptionSuffixInterface $interception
     *
     * @return string
     */
    private function calculateKey(CacheAnnotationInterface $annotation, InterceptionSuffixInterface $interception)
    {
        return $this->keyGenerator->generateKey($interception->getParams(), $annotation->key);
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
