<?php
declare(strict_types=1);

namespace Phpro\AnnotatedCache\Interceptor;

use Phpro\AnnotatedCache\Annotation\Cacheable;
use Phpro\AnnotatedCache\Annotation\CacheAnnotationInterface;
use Phpro\AnnotatedCache\Interception\InterceptionSuffixInterface;
use Phpro\AnnotatedCache\Interceptor\Result\EmptyResult;
use Phpro\AnnotatedCache\Interceptor\Result\MissResult;
use Phpro\AnnotatedCache\Interceptor\Result\ResultInterface;

/**
 * Class DoctrineCommonCacheableInterceptor
 *
 * @package Phpro\AnnotatedCache\Interceptor
 * @author Insekticid <insekticid@exploit.cz>
 */
class DoctrineCommonCacheableInterceptor extends CacheableInterceptor
{
    /**
     * @param Cacheable|CacheAnnotationInterface $annotation
     * @param InterceptionSuffixInterface $interception
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

        foreach ($annotation->pools as $poolName) {
            $pool = $this->getPoolManager()->getPool($poolName);
            /** @var \Symfony\Component\Cache\CacheItem $item */
            $item = $pool->getItem($key);
            $item->set($interception->getReturnValue());
            $item->tag($annotation->tags);

            if ($annotation->ttl > 0) {
                $item->expiresAfter($annotation->ttl);
            }

            $pool->save($item);
        }
        
        return new MissResult($interception, $key, $annotation->pools);
    }
}
