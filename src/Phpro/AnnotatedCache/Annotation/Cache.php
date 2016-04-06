<?php

namespace Phpro\AnnotatedCache\Annotation;

use Phpro\AnnotatedCache\Exception\InvalidArgumentException;

/**
 * Class CacheAnnotation
 *
 * @Annotation
 * @package Tbbc\CacheBundle\Annotation
 */
abstract class CacheAnnotation implements CacheAnnotationInterface
{
    /**
     * @var array|string
     */
    public $pools;

    /**
     * @var string
     */
    public $key;

    /**
     * @var array|string
     */
    public $tags;

    public function __construct(array $values)
    {
        if (!isset($values['pools'])) {
            throw new InvalidArgumentException('You must define a "pools" attribute for each Cacheable annotation.');
        }

        $this->caches = array_map('trim', explode(',', $values['pools']));

        if (isset($values['key'])) {
            $this->key = $values['key'];
        }

        if (isset($values['tags'])) {
            $this->tags = array_map('trim', explode(',', $values['tags']));
        }
    }
}
