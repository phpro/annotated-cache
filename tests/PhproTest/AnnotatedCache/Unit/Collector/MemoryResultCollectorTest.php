<?php

namespace PhproTest\AnnotatedCache\Unit\Collector;

use Phpro\AnnotatedCache\Collection\ResultCollection;
use Phpro\AnnotatedCache\Collector\MemoryResultCollector;
use Phpro\AnnotatedCache\Collector\ResultCollectorInterface;
use Phpro\AnnotatedCache\Interceptor\Result\EmptyResult;
use Phpro\AnnotatedCache\Interceptor\Result\ResultInterface;

/**
 * Class MemoryResultCollectorTest
 *
 * @package PhproTest\AnnotatedCache\Unit
 */
class MemoryResultCollectorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var MemoryResultCollector
     */
    private $collector;

    protected function setUp()
    {
        $this->collector = new MemoryResultCollector();
    }

    /**
     * @test
     */
    function it_is_a_result_collector()
    {
        $this->assertInstanceOf(ResultCollectorInterface::class, $this->collector);
    }

    /**
     * @test
     */
    function it_can_collect_results()
    {
        $result = $this->getMockBuilder(ResultInterface::class)->getMock();
        $this->collector->collect($result);
        $results = $this->collector->getResults();

        $this->assertEquals(1, $results->count());
    }

    /**
     * @test
     */
    function it_skips_empty_results()
    {
        $this->collector->collect(new EmptyResult());
        $results = $this->collector->getResults();

        $this->assertEquals(0, $results->count());
    }

    /**
     * @test
     */
    function it_returns_collected_results()
    {
        $result = $this->getMockBuilder(ResultInterface::class)->getMock();
        $this->collector->collect($result);
        $results = $this->collector->getResults();

        $this->assertInstanceOf(ResultCollection::class, $results);
        $this->assertEquals(1, $results->count());
        $this->assertSame($result, $results->first());
    }
}
