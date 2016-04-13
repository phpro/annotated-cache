<?php

namespace PhproTest\AnnotatedCache\Unit\Interceptor\Result;

use Phpro\AnnotatedCache\Interception\InterceptionInterface;
use Phpro\AnnotatedCache\Interceptor\Result\ContentAwareResultInterface;
use Phpro\AnnotatedCache\Interceptor\Result\HitResult;
use Phpro\AnnotatedCache\Interceptor\Result\HittableResultInterface;
use PhproTest\AnnotatedCache\Objects\BookService;

/**
 * Class HitResultTest
 *
 * @package PhproTest\AnnotatedCache\Unit\Interceptor\Result
 */
class HitResultTest extends AbstractResultTest
{

    /**
     * @var HitResult
     */
    protected $result;

    protected function setUp()
    {
        parent::setUp();

        $this->result = new HitResult($this->interception, 'key', ['pool'], 'cached');
    }

    /**
     * @test
     */
    function it_is_a_hittable_result()
    {
        $this->assertInstanceOf(HittableResultInterface::class, $this->result);
    }

    /**
     * @test
     */
    function it_is_a_content_aware_result()
    {
        $this->assertInstanceOf(ContentAwareResultInterface::class, $this->result);
    }

    /**
     * @test
     */
    function it_is_a_hit()
    {
        $this->assertTrue($this->result->isCacheHit());
    }

    /**
     * @test
     */
    function it_has_content()
    {
        $this->assertTrue($this->result->hasContent());
        $this->assertEquals('cached', $this->result->getContent());
    }
}
