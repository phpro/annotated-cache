<?php

namespace Phpro\AnnotatedCache\Interceptor;

use Cache\Taggable\TaggablePoolInterface;
use Phpro\AnnotatedCache\Annotation\CacheAnnotationInterface;
use Phpro\AnnotatedCache\Annotation\CacheEvict;
use Phpro\AnnotatedCache\Cache\PoolManager;
use Phpro\AnnotatedCache\Interception\InterceptionPrefixInterface;
use Phpro\AnnotatedCache\Interception\InterceptionSuffixInterface;
use Phpro\AnnotatedCache\KeyGenerator\KeyGeneratorInterface;
use Psr\Cache\CacheItemPoolInterface;

/**
 * Class CacheEvictInterceptor
 *
 * @package Phpro\AnnotatedCache\Interceptor
 */
class CacheEvictInterceptor implements InterceptorInterface
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
        return $annotation instanceof CacheEvict;
    }

    /**
     * @param CacheEvict                  $annotation
     * @param InterceptionPrefixInterface $interception
     */
    public function interceptPrefix(CacheAnnotationInterface $annotation, InterceptionPrefixInterface $interception)
    {
        return null;
    }

    /**
     * @param CacheEvict                  $annotation
     * @param InterceptionSuffixInterface $interception
     */
    public function interceptSuffix(CacheAnnotationInterface $annotation, InterceptionSuffixInterface $interception)
    {
        foreach ($annotation->pools as $poolName) {
            $pool = $this->poolManager->getPool('pool');
            $this->evictKey($pool, $this->calculateKey($annotation, $interception));
            $this->evictTags($pool, $annotation->tags);
        }
    }

    /**
     * @param CacheEvict    $annotation
     * @param InterceptionSuffixInterface $interception
     *
     * @return string
     */
    private function calculateKey(CacheAnnotationInterface $annotation, InterceptionSuffixInterface $interception)
    {
        return $this->keyGenerator->generateKey($interception->getParams(), $annotation->key);
    }

    /**
     * @param CacheItemPoolInterface $pool
     * @param                        $key
     */
    private function evictKey(CacheItemPoolInterface $pool, $key)
    {
        if (!$pool->hasItem($key)) {
            return;
        }

        $pool->deleteItem($key);
    }

    /**
     * @param CacheItemPoolInterface $pool
     * @param array                  $tags
     */
    private function evictTags(CacheItemPoolInterface $pool, array $tags)
    {
        if (!$pool instanceof TaggablePoolInterface || count($tags) === 0) {
            return;
        }

        $pool->clearTags($tags);
    }
}
