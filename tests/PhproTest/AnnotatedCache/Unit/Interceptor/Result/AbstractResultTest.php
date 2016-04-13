<?php


namespace PhproTest\AnnotatedCache\Unit\Interceptor\Result;

use Phpro\AnnotatedCache\Interception\InterceptionInterface;
use Phpro\AnnotatedCache\Interceptor\Result\ResultInterface;
use PhproTest\AnnotatedCache\Objects\BookService;

/**
 * Class AbstractResultTest
 *
 * @package PhproTest\AnnotatedCache\Unit\Interceptor\Result
 */
abstract class AbstractResultTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var InterceptionInterface
     */
    protected $interception;

    /**
     * @var ResultInterface
     */
    protected $result;

    protected function setUp()
    {
        $this->interception = $this->getMockWithoutInvokingTheOriginalConstructor(InterceptionInterface::class);
        $this->interception->method('getInstance')->willReturn(new BookService());
        $this->interception->method('getMethod')->willReturn('getBookByIsbn');
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
    function it_has_a_class()
    {
        $this->assertEquals(BookService::class, $this->result->getClassName());
    }

    /**
     * @test
     */
    function it_has_a_method()
    {
        $this->assertEquals('getBookByIsbn', $this->result->getMethod());
    }

    /**
     * @test
     */
    function it_has_a_key()
    {
        $this->assertEquals('key', $this->result->getKey());
    }

    /**
     * @test
     */
    function it_has_pools()
    {
        $this->assertEquals(['pool'], $this->result->getPools());
    }
}
