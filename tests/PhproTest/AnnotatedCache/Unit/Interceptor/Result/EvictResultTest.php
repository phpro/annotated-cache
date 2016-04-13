<?php

namespace PhproTest\AnnotatedCache\Unit\Interceptor\Result;

use Phpro\AnnotatedCache\Interception\InterceptionInterface;
use Phpro\AnnotatedCache\Interceptor\Result\ContentAwareResultInterface;
use Phpro\AnnotatedCache\Interceptor\Result\EvictResult;
use Phpro\AnnotatedCache\Interceptor\Result\HittableResultInterface;
use Phpro\AnnotatedCache\Interceptor\Result\TagsAwareResultInterface;
use PhproTest\AnnotatedCache\Objects\BookService;

/**
 * Class EvictResultTest
 *
 * @package PhproTest\AnnotatedCache\Unit\Interceptor\Result
 */
class EvictResultTest extends AbstractResultTest
{

    /**
     * @var EvictResult
     */
    protected $result;

    protected function setUp()
    {
        parent::setUp();

        $this->result = new EvictResult($this->interception, 'key', ['pool'], ['tags']);
    }

    /**
     * @test
     */
    function it_is_a_tag_aware_result()
    {
        $this->assertInstanceOf(TagsAwareResultInterface::class, $this->result);
    }

    /**
     * @test
     */
    function it_has_tags()
    {
        $this->assertEquals(['tags'], $this->result->getTags());
    }
}
