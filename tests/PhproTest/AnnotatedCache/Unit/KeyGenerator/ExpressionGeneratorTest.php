<?php

namespace PhproTest\AnnotatedCache\Unit\KeyGenerator;


use Phpro\AnnotatedCache\KeyGenerator\ExpressionGenerator;
use Phpro\AnnotatedCache\KeyGenerator\KeyGeneratorInterface;
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
        $this->innerGenerator->method('generateKey')->with(['expression' => 'value1value2'])->willReturn('success');
        $result = $this->keyGenerator->generateKey(['key1' => 'value1', 'key2' => 'value2'], 'key1 ~ key2');

        $this->assertEquals('success', $result);
    }

    /**
     * @test
     */
    function it_skips_expressions_when_no_key_is_entered()
    {
        $params = ['key1' => 'value1', 'key2' => 'value2'];
        $this->innerGenerator->method('generateKey')->with($params)->willReturn('success');
        $result = $this->keyGenerator->generateKey($params);

        $this->assertEquals('success', $result);
    }
}
