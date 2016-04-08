<?php
declare(strict_types=1);

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
    public $pools = [];

    /**
     * @var string
     */
    public $key;

    /**
     * @var array|string
     */
    public $tags = [];

    public function __construct(array $values)
    {
        if (!isset($values['pools'])) {
            throw new InvalidArgumentException('You must define a "pools" attribute for each Cacheable annotation.');
        }

        $pools = $values['pools'];
        $this->pools = is_array($pools) ? $pools :  array_map('trim', explode(',', $pools));

        if (isset($values['key'])) {
            $this->key = $values['key'];
        }

        if (isset($values['tags'])) {
            $tags = $values['tags'];
            $this->tags = is_array($tags) ? $tags :  array_map('trim', explode(',', $tags));
        }
    }
}
