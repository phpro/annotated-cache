<?php

namespace PhproTest\AnnotatedCache\Unit\Interception;

use Phpro\AnnotatedCache\Interception\InterceptionSuffixInterface;
use Phpro\AnnotatedCache\Interception\ProxyInterceptionSuffix;

class ProxyInterceptionSuffixTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ProxyInterceptionSuffix
     */
    private $proxyInterceptionSuffix;

    protected function setUp()
    {
        $this->proxyInterceptionSuffix = new ProxyInterceptionSuffix(
            new \stdClass(),
            'method',
            ['key1' => 'value1'],
            'returnValue'
        );
    }

    /**
     * @test
     */
    function it_is_an_interception_prefix()
    {
        $this->assertInstanceOf(InterceptionSuffixInterface::class, $this->proxyInterceptionSuffix);
    }

    /**
     * @test
     */
    function it_has_an_object()
    {
        $this->assertEquals(new \stdClass(), $this->proxyInterceptionSuffix->getInstance());
    }

    /**
     * @test
     */
    function it_has_a_method()
    {
        $this->assertEquals('method', $this->proxyInterceptionSuffix->getMethod());
    }

    /**
     * @test
     */
    function it_has_params()
    {
        $this->assertEquals(['key1' => 'value1'], $this->proxyInterceptionSuffix->getParams());
    }

    /**
     * @test
     */
    function it_has_a_return_value()
    {
        $this->assertEquals('returnValue', $this->proxyInterceptionSuffix->getReturnValue());
    }
}
