<?php

namespace PhproTest\AnnotatedCache\Unit\Cache;

use Phpro\AnnotatedCache\Annotation\CacheAnnotationInterface;
use Phpro\AnnotatedCache\Cache\CacheHandler;
use Phpro\AnnotatedCache\Cache\CacheHandlerInterface;
use Phpro\AnnotatedCache\Collection\AnnotationCollection;
use Phpro\AnnotatedCache\Interception\ProxyInterceptionPrefix;
use Phpro\AnnotatedCache\Interception\ProxyInterceptionSuffix;
use Phpro\AnnotatedCache\Interceptor\InterceptorInterface;

/**
 * Class CacheHandlerTest
 *
 * @package PhproTest\AnnotatedCache\Unit
 */
class CacheHandlerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var CacheHandler
     */
    private $cacheHandler;

    /**
     * @var InterceptorInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $interceptor;

    /**
     * @var CacheAnnotationInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $annotation;

    protected function setUp()
    {
        $this->annotation = $this->getMockBuilder(CacheAnnotationInterface::class)->getMock();
        $this->interceptor = $this->getMockBuilder(InterceptorInterface::class)->getMock();
        $this->cacheHandler = new CacheHandler();
        $this->cacheHandler->addInterceptor($this->interceptor);
    }

    /**
     * @test
     */
    function it_is_a_cache_handler()
    {
        $this->assertInstanceOf(CacheHandlerInterface::class, $this->cacheHandler);
    }

    /**
     * @test
     */
    function it_intercepts_proxy_prefix()
    {
        $interception = new ProxyInterceptionPrefix(new \stdClass, 'someMethod', ['key1' => 'param1']);

        $this->interceptor
            ->method('canInterceptAnnotation')
            ->with($this->annotation)
            ->willReturn(true);

        $this->interceptor
            ->method('interceptPrefix')
            ->with(
                $this->annotation,
                $interception
            )
            ->willReturn('somevalue');

        $result = $this->cacheHandler->interceptProxyPrefix(
            new AnnotationCollection([$this->annotation]),
            $interception
        );
        $this->assertEquals('somevalue', $result);
    }

    /**
     * @test
     */
    function it_intercepts_proxy_suffix()
    {
        $interception = new ProxyInterceptionSuffix(new \stdClass, 'someMethod', ['key1' => 'param1'], 'returnValue');

        $this->interceptor
            ->method('canInterceptAnnotation')
            ->with($this->annotation)
            ->willReturn(true);

        $this->interceptor
            ->method('interceptSuffix')
            ->with(
                $this->annotation,
                $interception
            )
            ->willReturn('someNewValue');

        $result = $this->cacheHandler->interceptProxySuffix(
            new AnnotationCollection([$this->annotation]),
            $interception
        );
        $this->assertEquals('someNewValue', $result);
    }
}
