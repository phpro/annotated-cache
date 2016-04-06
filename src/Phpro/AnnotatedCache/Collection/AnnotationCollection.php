<?php

namespace Phpro\AnnotatedCache\Collection;

use Doctrine\Common\Collections\ArrayCollection;
use Phpro\AnnotatedCache\Annotation\Cacheable;
use Phpro\AnnotatedCache\Annotation\CacheEvict;
use Phpro\AnnotatedCache\Annotation\CacheAnnotationInterface;
use Phpro\AnnotatedCache\Annotation\CacheUpdate;

/**
 * Class AnnotationCollection
 *
 * @package Phpro\AnnotatedCache\Collection
 */
class AnnotationCollection extends ArrayCollection
{
    /**
     * @return AnnotationCollection
     */
    public function getCacheable()
    {
        return $this->filter(function(CacheAnnotationInterface $annotation) {
            return $annotation instanceof Cacheable;
        });
    }

    /**
     * @return AnnotationCollection
     */
    public function getCacheEvicts()
    {
        return $this->filter(function(CacheAnnotationInterface $annotation) {
            return $annotation instanceof CacheEvict;
        });
    }

    /**
     * @return AnnotationCollection
     */
    public function getCacheUpdates()
    {
        return $this->filter(function(CacheAnnotationInterface $annotation) {
            return $annotation instanceof CacheUpdate;
        });
    }
}
