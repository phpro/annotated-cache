<?php
declare(strict_types=1);

namespace Phpro\AnnotatedCache\Interceptor;

use Cache\Adapter\Common\CacheItem;
use Phpro\AnnotatedCache\Annotation\CacheAnnotationInterface;
use Phpro\AnnotatedCache\Annotation\CacheUpdate;
use Phpro\AnnotatedCache\Cache\PoolManagerInterface;
use Phpro\AnnotatedCache\Interception\InterceptionInterface;
use Phpro\AnnotatedCache\Interception\InterceptionPrefixInterface;
use Phpro\AnnotatedCache\Interception\InterceptionSuffixInterface;
use Phpro\AnnotatedCache\Interceptor\Result\EmptyResult;
use Phpro\AnnotatedCache\Interceptor\Result\ResultInterface;
use Phpro\AnnotatedCache\Interceptor\Result\UpdateResult;
use Phpro\AnnotatedCache\KeyGenerator\KeyGeneratorInterface;

/**
 * Class CacheUpdateInterceptor
 *
 * @package Phpro\AnnotatedCache\Interceptor
 */
class CacheUpdateInterceptor implements InterceptorInterface
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
        return $annotation instanceof CacheUpdate;
    }

    /**
     * @param CacheUpdate                 $annotation
     * @param InterceptionPrefixInterface $interception
     *
     * @return ResultInterface
     */
    public function interceptPrefix(
        CacheAnnotationInterface $annotation,
        InterceptionPrefixInterface $interception
    ) : ResultInterface {
        return new EmptyResult();
    }

    /**
     * @param CacheUpdate                 $annotation
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

        return new UpdateResult($interception, $key, $annotation->pools);
    }

    /**
     * @param CacheAnnotationInterface $annotation
     * @param InterceptionInterface    $interception
     *
     * @return string
     */
    private function calculateKey(CacheAnnotationInterface $annotation, InterceptionInterface $interception) : string
    {
        return $this->keyGenerator->generateKey($interception, $annotation);
    }
}
