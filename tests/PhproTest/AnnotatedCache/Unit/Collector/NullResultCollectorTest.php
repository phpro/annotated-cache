<?php

namespace PhproTest\AnnotatedCache\Unit\Collector;

use Phpro\AnnotatedCache\Collection\ResultCollection;
use Phpro\AnnotatedCache\Collector\NullResultCollector;
use Phpro\AnnotatedCache\Collector\ResultCollectorInterface;
use Phpro\AnnotatedCache\Interceptor\Result\EmptyResult;
use Phpro\AnnotatedCache\Interceptor\Result\ResultInterface;

/**
 * Class NullResultCollectorTest
 *
 * @package PhproTest\AnnotatedCache\Unit
 */
class NullResultCollectorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var NullResultCollector
     */
    private $collector;

    protected function setUp()
    {
        $this->collector = new NullResultCollector();
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
        $this->assertEquals(0, $results->count());
    }
}
