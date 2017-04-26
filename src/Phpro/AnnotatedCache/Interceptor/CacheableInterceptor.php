<?php
declare(strict_types=1);

namespace Phpro\AnnotatedCache\Interceptor;

use Cache\Adapter\Common\CacheItem;
use Phpro\AnnotatedCache\Annotation\Cacheable;
use Phpro\AnnotatedCache\Annotation\CacheAnnotationInterface;
use Phpro\AnnotatedCache\Cache\PoolManagerInterface;
use Phpro\AnnotatedCache\Exception\RuntimeException;
use Phpro\AnnotatedCache\Interception\InterceptionInterface;
use Phpro\AnnotatedCache\Interception\InterceptionPrefixInterface;
use Phpro\AnnotatedCache\Interception\InterceptionSuffixInterface;
use Phpro\AnnotatedCache\Interceptor\Result\EmptyResult;
use Phpro\AnnotatedCache\Interceptor\Result\HitResult;
use Phpro\AnnotatedCache\Interceptor\Result\MissResult;
use Phpro\AnnotatedCache\Interceptor\Result\ResultInterface;
use Phpro\AnnotatedCache\KeyGenerator\KeyGeneratorInterface;

/**
 * Class CacheableInterceptor
 *
 * @package Phpro\AnnotatedCache\Interceptor
 */
class CacheableInterceptor implements InterceptorInterface
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
        return $annotation instanceof Cacheable;
    }

    /**
     * @param Cacheable                   $annotation
     * @param InterceptionPrefixInterface $interception
     *
     * @return ResultInterface
     */
    public function interceptPrefix(
        CacheAnnotationInterface $annotation,
        InterceptionPrefixInterface $interception
    ) : ResultInterface {
        $key = $this->calculateKey($annotation, $interception);
        foreach ($annotation->pools as $poolName) {
            if (!$cacheItem = $this->locateCachedItem($poolName, $key)) {
                continue;
            }
            
            return new HitResult($interception, $key, [$poolName], $cacheItem->get());
        }
        
        return new EmptyResult();
    }

    /**
     * @param Cacheable                   $annotation
     * @param InterceptionSuffixInterface $interception
     *
     * @return ResultInterface
     */
    public function interceptSuffix(
        CacheAnnotationInterface $annotation,
        InterceptionSuffixInterface $interception
    ) : ResultInterface {
        if (!$interception->getReturnValue()) {
            return new EmptyResult();
        }

        $key = $this->calculateKey($annotation, $interception);

        $item = new CacheItem($key);
        $item->set($interception->getReturnValue());
        $item->setTags($annotation->tags);

        if ($annotation->ttl > 0) {
            $item->expiresAfter($annotation->ttl);
        }

        foreach ($annotation->pools as $poolName) {
            $pool = $this->poolManager->getPool($poolName);
            $pool->save($item);
        }
        
        return new MissResult($interception, $key, $annotation->pools);
    }

    /**
     * @param CacheAnnotationInterface $annotation
     * @param InterceptionInterface    $interception
     *
     * @return string
     */
    protected function calculateKey(CacheAnnotationInterface $annotation, InterceptionInterface $interception) : string
    {
        return $this->keyGenerator->generateKey($interception, $annotation);
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

    /**
     * @return PoolManagerInterface
     */
    protected function getPoolManager(): PoolManagerInterface
    {
        return $this->poolManager;
    }
}
