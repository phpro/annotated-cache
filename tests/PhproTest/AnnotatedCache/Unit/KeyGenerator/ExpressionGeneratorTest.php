<?php

namespace PhproTest\AnnotatedCache\Unit\KeyGenerator;

use Phpro\AnnotatedCache\KeyGenerator\ExpressionGenerator;
use Phpro\AnnotatedCache\KeyGenerator\KeyGeneratorInterface;
use PhproTest\AnnotatedCache\Objects\TestAnnotation;
use PhproTest\AnnotatedCache\Objects\TestInterception;
use Zend\Code\Generator\GeneratorInterface;

class ExpressionGeneratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var GeneratorInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $innerGenerator;

    /**
     * @var ExpressionGenerator
     */
    private $keyGenerator;

    protected function setUp()
    {
        $this->innerGenerator = $this->getMockBuilder(KeyGeneratorInterface::class)->getMock();
        $this->keyGenerator = new ExpressionGenerator($this->innerGenerator);
    }

    /**
     * @test
     */
    function it_is_a_key_generator()
    {
        $this->assertInstanceOf(KeyGeneratorInterface::class, $this->keyGenerator);
    }

    /**
     * @test
     */
    function it_generates_key_with_evaluations()
    {
        $interception = new TestInterception(null, 'method', ['key1' => 'value1', 'key2' => 'value2']);
        $annotation = new TestAnnotation(['key' => 'key1 ~ key2']);

        $expected = sha1(serialize('value1value2'));
        $this->assertEquals($expected, $this->keyGenerator->generateKey($interception, $annotation));
    }

    /**
     * @test
     */
    function it_generates_key_with_interception_context()
    {
        $interception = new TestInterception(null, 'method', ['key1' => 'value1', 'key2' => 'value2']);
        $annotation = new TestAnnotation(['key' => 'interception.getMethod()']);

        $expected = sha1(serialize('method'));
        $this->assertEquals($expected, $this->keyGenerator->generateKey($interception, $annotation));
    }


    /**
     * @test
     */
    function it_skips_expressions_when_no_key_is_entered()
    {
        $interception = new TestInterception(null, 'method', ['key1' => 'value1', 'key2' => 'value2']);
        $annotation = new TestAnnotation(['key' => '']);

        $this->innerGenerator->method('generateKey')->with($interception, $annotation)->willReturn('success');
        $result = $this->keyGenerator->generateKey($interception, $annotation);

        $this->assertEquals('success', $result);
    }
}
