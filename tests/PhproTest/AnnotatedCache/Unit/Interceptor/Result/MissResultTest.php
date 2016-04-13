<?php

namespace PhproTest\AnnotatedCache\Unit\Interceptor\Result;

use Phpro\AnnotatedCache\Interception\InterceptionInterface;
use Phpro\AnnotatedCache\Interceptor\Result\ContentAwareResultInterface;
use Phpro\AnnotatedCache\Interceptor\Result\MissResult;
use Phpro\AnnotatedCache\Interceptor\Result\HittableResultInterface;
use PhproTest\AnnotatedCache\Objects\BookService;

/**
 * Class MissResultTest
 *
 * @package PhproTest\AnnotatedCache\Unit\Interceptor\Result
 */
class MissResultTest extends AbstractResultTest
{

    /**
     * @var MissResult
     */
    protected $result;

    protected function setUp()
    {
        parent::setUp();

        $this->result = new MissResult($this->interception, 'key', ['pool']);
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
    function it_is_not_a_hit()
    {
        $this->assertFalse($this->result->isCacheHit());
    }
}
