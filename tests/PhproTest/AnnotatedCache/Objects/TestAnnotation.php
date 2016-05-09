<?php

namespace PhproTest\AnnotatedCache\Objects;

use Phpro\AnnotatedCache\Annotation\CacheAnnotation;

/**
 * Class TestAnnotation
 *
 * @package PhproTest\AnnotatedCache\Objects
 */
class TestAnnotation extends CacheAnnotation
{
    /**
     * TestAnnotation constructor.
     *
     * @param array $values
     */
    public function __construct(array $values)
    {
        $values['pools'] = $values['pools'] ?? 'pool';
        parent::__construct($values);
    }
}
