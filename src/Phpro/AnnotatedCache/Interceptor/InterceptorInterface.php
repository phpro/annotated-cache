<?php

namespace Phpro\AnnotatedCache\Interceptor;

use Phpro\AnnotatedCache\Annotation\CacheAnnotationInterface;
use Phpro\AnnotatedCache\Interception\InterceptionPrefixInterface;
use Phpro\AnnotatedCache\Interception\InterceptionSuffixInterface;

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
    public function canInterceptAnnotation(CacheAnnotationInterface $annotation);

    /**
     * @param CacheAnnotationInterface    $annotation
     * @param InterceptionPrefixInterface $interception
     *
     * @return mixed
     */
    public function interceptPrefix(CacheAnnotationInterface $annotation, InterceptionPrefixInterface $interception);

    /**
     * @param CacheAnnotationInterface    $annotation
     * @param InterceptionSuffixInterface $interception
     *
     * @return mixed
     */
    public function interceptSuffix(CacheAnnotationInterface $annotation, InterceptionSuffixInterface $interception);
}
