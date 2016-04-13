<?php
declare(strict_types=1);

namespace Phpro\AnnotatedCache\Interceptor;

use Phpro\AnnotatedCache\Annotation\CacheAnnotationInterface;
use Phpro\AnnotatedCache\Interception\InterceptionPrefixInterface;
use Phpro\AnnotatedCache\Interception\InterceptionSuffixInterface;
use Phpro\AnnotatedCache\Interceptor\Result\ResultInterface;

/**
 * Interface InterceptorInterface
 *
 * @package Phpro\AnnotatedCache\Interceptor
 */
interface InterceptorInterface
{

    /**
     * @param CacheAnnotationInterface $annotation
     *
     * @return bool
     */
    public function canInterceptAnnotation(CacheAnnotationInterface $annotation) : bool;

    /**
     * @param CacheAnnotationInterface    $annotation
     * @param InterceptionPrefixInterface $interception
     *
     * @return ResultInterface
     */
    public function interceptPrefix(
        CacheAnnotationInterface $annotation,
        InterceptionPrefixInterface $interception
    ) : ResultInterface;

    /**
     * @param CacheAnnotationInterface    $annotation
     * @param InterceptionSuffixInterface $interception
     *
     * @return ResultInterface
     */
    public function interceptSuffix(
        CacheAnnotationInterface $annotation,
        InterceptionSuffixInterface $interception
    ) : ResultInterface;
}
