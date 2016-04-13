<?php

namespace PhproTest\AnnotatedCache\Unit\Cache;

use Phpro\AnnotatedCache\Annotation\CacheAnnotationInterface;
use Phpro\AnnotatedCache\Cache\CacheHandler;
use Phpro\AnnotatedCache\Cache\CacheHandlerInterface;
use Phpro\AnnotatedCache\Collection\AnnotationCollection;
use Phpro\AnnotatedCache\Collector\NullResultCollector;
use Phpro\AnnotatedCache\Collector\ResultCollectorInterface;
use Phpro\AnnotatedCache\Interception\ProxyInterceptionPrefix;
use Phpro\AnnotatedCache\Interception\ProxyInterceptionSuffix;
use Phpro\AnnotatedCache\Interceptor\InterceptorInterface;
use Phpro\AnnotatedCache\Interceptor\Result\HitResult;

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
     * @var ResultCollectorInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $resultCollector;

    /**
     * @var CacheAnnotationInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $annotation;

    protected function setUp()
    {
        $this->annotation = $this->getMockBuilder(CacheAnnotationInterface::class)->getMock();
        $this->interceptor = $this->getMockBuilder(InterceptorInterface::class)->getMock();
        $this->resultCollector = $this->getMockBuilder(ResultCollectorInterface::class)->getMock();

        $this->cacheHandler = new CacheHandler($this->resultCollector);
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
        $result = new HitResult($interception, 'key', ['pool'], 'somevalue');

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
            ->willReturn($result);

        $this->resultCollector->expects($this->once())->method('collect')->with($result);

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
        $result = new HitResult($interception, 'key', ['pool'], 'someNewValue');

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
            ->willReturn($result);

        $this->resultCollector->expects($this->once())->method('collect')->with($result);

        $result = $this->cacheHandler->interceptProxySuffix(
            new AnnotationCollection([$this->annotation]),
            $interception
        );
        $this->assertEquals('someNewValue', $result);
    }
}
