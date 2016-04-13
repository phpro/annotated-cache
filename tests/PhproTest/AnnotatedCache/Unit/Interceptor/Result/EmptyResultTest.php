<?php

namespace PhproTest\AnnotatedCache\Unit\Interceptor\Result;

use Phpro\AnnotatedCache\Interceptor\Result\EmptyResult;
use Phpro\AnnotatedCache\Interceptor\Result\ResultInterface;

/**
 * Class EmptyResultTest
 *
 * @package PhproTest\AnnotatedCache\Unit\Interceptor\Result
 */
class EmptyResultTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var EmptyResult
     */
    private $result;

    protected function setUp()
    {
        $this->result = new EmptyResult();
    }

    /**
     * @test
     */
    function it_is_a_result()
    {
        $this->assertInstanceOf(ResultInterface::class, $this->result);
    }

    /**
     * @test
     */
    function it_has_an_empty_class()
    {
        $this->assertEquals('', $this->result->getClassName());
    }

    /**
     * @test
     */
    function it_has_an_empty_method()
    {
        $this->assertEquals('', $this->result->getMethod());
    }

    /**
     * @test
     */
    function it_has_an_empty_key()
    {
        $this->assertEquals('', $this->result->getKey());
    }

    /**
     * @test
     */
    function it_has_empty_pools()
    {
        $this->assertEquals([], $this->result->getPools());
    }
}
