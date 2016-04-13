<?php

namespace PhproTest\AnnotatedCache\Unit\Collection;

use Doctrine\Common\Collections\ArrayCollection;
use Phpro\AnnotatedCache\Collection\ResultCollection;
use Phpro\AnnotatedCache\Interception\InterceptionInterface;
use Phpro\AnnotatedCache\Interceptor\Result\EmptyResult;
use Phpro\AnnotatedCache\Interceptor\Result\HitResult;
use Phpro\AnnotatedCache\Interceptor\Result\MissResult;

/**
 * Class ResultCollectionTest
 *
 * @package PhproTest\AnnotatedCache\Unit
 */
class ResultCollectionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    function it_is_an_array_collection()
    {
        $this->assertInstanceOf(ArrayCollection::class, new ResultCollection());
    }

    /**
     * @test
     */
    function it_can_filter_based_on_type()
    {
        $collection = $this->mockCollection();
        $filtered = $collection->filterByType(EmptyResult::class);
        $this->assertEquals(1, $filtered->count());
    }

    /**
     * @test
     */
    function it_can_count_the_amount_of_hits()
    {
        $collection = $this->mockCollection();
        $this->assertEquals(1, $collection->countHits());
    }

    /**
     * @test
     */
    function it_can_count_the_amount_of_misses()
    {
        $collection = $this->mockCollection();
        $this->assertEquals(1, $collection->countMisses());
    }

    /**
     * @return ResultCollection
     */
    private function mockCollection()
    {
        $interception = $this->getMockBuilder(InterceptionInterface::class)->getMock();

        return new ResultCollection([
            new HitResult($interception, 'key', ['pool'], 'hit'),
            new EmptyResult(),
            new MissResult($interception, 'key', ['pool']),
        ]);
    }
}
