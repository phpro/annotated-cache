<?php
declare(strict_types=1);

namespace Phpro\AnnotatedCache\Cache;

use Phpro\AnnotatedCache\Collection\AnnotationCollection;
use Phpro\AnnotatedCache\Interception\InterceptionPrefixInterface;
use Phpro\AnnotatedCache\Interception\InterceptionSuffixInterface;

/**
 * Class CacheHandler
 *
 * @package Phpro\AnnotatedCache\CacheAnnotation
 */
interface CacheHandlerInterface
{
    /**
     * @param AnnotationCollection        $annotations
     * @param InterceptionPrefixInterface $interception
     *
     * @return mixed
     */
    public function interceptProxyPrefix(AnnotationCollection $annotations, InterceptionPrefixInterface $interception);

    /**
     * @param AnnotationCollection        $annotations
     * @param InterceptionSuffixInterface $interception
     *
     * @return mixed
     */
    public function interceptProxySuffix(AnnotationCollection $annotations, InterceptionSuffixInterface $interception);
}
