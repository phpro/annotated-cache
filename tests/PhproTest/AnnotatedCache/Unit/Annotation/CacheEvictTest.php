<?php

namespace PhproTest\AnnotatedCache\Unit\Annotation;

use Phpro\AnnotatedCache\Annotation\CacheEvict;
use Phpro\AnnotatedCache\Annotation\CacheAnnotationInterface;

class CacheEvictTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    function it_is_a_cache_annotation()
    {
        $this->assertInstanceOf(CacheAnnotationInterface::class, new CacheEvict(['pools' => 'pool']));
    }

    /**
     * @test
     */
    function it_is_configurable()
    {
        $annotation = new CacheEvict([
            'pools' => 'pool1, pool2',
            'key' => 'mykey',
            'tags' => 'tag1, tag2',
        ]);

        $this->assertEquals(['pool1', 'pool2'], $annotation->pools);
        $this->assertEquals('mykey', $annotation->key);
        $this->assertEquals(['tag1', 'tag2'], $annotation->tags);
    }
}
