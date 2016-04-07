<?php

namespace PhproTest\AnnotatedCache\Unit\Collection;

use Doctrine\Common\Collections\ArrayCollection;
use Phpro\AnnotatedCache\Collection\AnnotationCollection;

/**
 * Class AnnotationCollectionTest
 *
 * @package PhproTest\AnnotatedCache\Unit
 */
class AnnotationCollectionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    function it_is_an_array_collection()
    {
        $this->assertInstanceOf(ArrayCollection::class, new AnnotationCollection());
    }

}
