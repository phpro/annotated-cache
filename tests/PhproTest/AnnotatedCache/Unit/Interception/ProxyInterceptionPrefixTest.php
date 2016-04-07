<?php

namespace PhproTest\AnnotatedCache\Unit\Interception;

use Phpro\AnnotatedCache\Interception\InterceptionPrefixInterface;
use Phpro\AnnotatedCache\Interception\ProxyInterceptionPrefix;

class ProxyInterceptionPrefixTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ProxyInterceptionPrefix
     */
    private $proxyInterceptionPrefix;

    protected function setUp()
    {
        $this->proxyInterceptionPrefix = new ProxyInterceptionPrefix(
            new \stdClass(),
            'method',
            ['key1' => 'value1']
        );
    }

    /**
     * @test
     */
    function it_is_an_interception_prefix()
    {
        $this->assertInstanceOf(InterceptionPrefixInterface::class, $this->proxyInterceptionPrefix);
    }

    /**
     * @test
     */
    function it_has_an_object()
    {
        $this->assertEquals(new \stdClass(), $this->proxyInterceptionPrefix->getInstance());
    }

    /**
     * @test
     */
    function it_has_a_method()
    {
        $this->assertEquals('method', $this->proxyInterceptionPrefix->getMethod());
    }

    /**
     * @test
     */
    function it_has_params()
    {
        $this->assertEquals(['key1' => 'value1'], $this->proxyInterceptionPrefix->getParams());
    }

}
