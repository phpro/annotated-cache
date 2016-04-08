<?php
declare(strict_types=1);

namespace Phpro\AnnotatedCache\Interceptor;

use Cache\Taggable\TaggablePoolInterface;
use Phpro\AnnotatedCache\Annotation\CacheAnnotationInterface;
use Phpro\AnnotatedCache\Annotation\CacheEvict;
use Phpro\AnnotatedCache\Cache\PoolManagerInterface;
use Phpro\AnnotatedCache\Interception\InterceptionInterface;
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
     * @var PoolManagerInterface
     */
    private $poolManager;

    /**
     * @var KeyGeneratorInterface
     */
    private $keyGenerator;

    /**
     * CacheableInterceptor constructor.
     *
     * @param PoolManagerInterface  $poolManager
     * @param KeyGeneratorInterface $keyGenerator
     */
    public function __construct(PoolManagerInterface $poolManager, KeyGeneratorInterface $keyGenerator)
    {
        $this->poolManager = $poolManager;
        $this->keyGenerator = $keyGenerator;
    }

    /**
     * @param CacheAnnotationInterface $annotation
     *
     * @return bool
     */
    public function canInterceptAnnotation(CacheAnnotationInterface $annotation) : bool
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
            $pool = $this->poolManager->getPool($poolName);
            $this->evictKey($pool, $this->calculateKey($annotation, $interception));
            $this->evictTags($pool, $annotation->tags);
        }
    }

    /**
     * @param CacheAnnotationInterface $annotation
     * @param InterceptionInterface    $interception
     *
     * @return string
     */
    private function calculateKey(CacheAnnotationInterface $annotation, InterceptionInterface $interception) : string
    {
        return $this->keyGenerator->generateKey($interception->getParams(), $annotation->key);
    }

    /**
     * @param CacheItemPoolInterface $pool
     * @param                        $key
     */
    private function evictKey(CacheItemPoolInterface $pool, $key)
    {
        if (!$key || !$pool->hasItem($key)) {
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
