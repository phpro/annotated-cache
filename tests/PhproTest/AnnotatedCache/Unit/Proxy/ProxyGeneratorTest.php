<?php

namespace PhproTest\AnnotatedCache\Unit\Proxy;

use Doctrine\Common\Annotations\AnnotationReader;
use Phpro\AnnotatedCache\Cache\CacheHandler;
use Phpro\AnnotatedCache\Collection\AnnotationCollection;
use Phpro\AnnotatedCache\Interception\InterceptionPrefixInterface;
use Phpro\AnnotatedCache\Interception\InterceptionSuffixInterface;
use Phpro\AnnotatedCache\Proxy\ProxyGenerator;
use PhproTest\AnnotatedCache\Objects\ProxyInstance;
use ProxyManager\Factory\AccessInterceptorValueHolderFactory;
use ProxyManager\Proxy\AccessInterceptorValueHolderInterface;

class ProxyGeneratorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var CacheHandler|\PHPUnit_Framework_MockObject_MockObject
     */
    private $cacheHandler;

    /**
     * @var ProxyGenerator
     */
    private $proxyGenerator;

    protected function setUp()
    {
        $this->cacheHandler = $this->getMockBuilder(CacheHandler::class)->disableOriginalConstructor()->getMock();
        $this->proxyGenerator = new ProxyGenerator(
            new AccessInterceptorValueHolderFactory(),
            new AnnotationReader(),
            $this->cacheHandler
        );
    }

    /**
     * @test
     */
    function it_generates_an_access_interceptor_proxy()
    {
        $proxy = $this->proxyGenerator->generate(new ProxyInstance());

        $this->assertInstanceOf(AccessInterceptorValueHolderInterface::class, $proxy);
        $this->assertInstanceOf(ProxyInstance::class, $proxy);
    }

    /**
     * @test
     */
    function it_attaches_the_cache_handler_interceptions()
    {
        $instance = new ProxyInstance();
        $this->cacheHandler
            ->expects($this->once())
            ->method('interceptProxyPrefix')
            ->with(
                $this->isInstanceOf(AnnotationCollection::class),
                $this->callback(function (InterceptionPrefixInterface $interception) use ($instance) {
                    return $interception->getInstance() === $instance
                        && $interception->getMethod() === 'triggerCache'
                        && $interception->getParams() === ['var' => 'value'];
                })
            );

        $this->cacheHandler
            ->expects($this->once())
            ->method('interceptProxySuffix')
            ->with(
                $this->isInstanceOf(AnnotationCollection::class),
                $this->callback(function (InterceptionSuffixInterface $interception) use ($instance) {
                    return $interception->getInstance() === $instance
                        && $interception->getMethod() === 'triggerCache'
                        && $interception->getParams() === ['var' => 'value']
                        && $interception->getReturnValue() === 'normal';
                })
            );

        /** @var ProxyInstance $proxy */
        $proxy = $this->proxyGenerator->generate($instance);
        $result = $proxy->triggerCache('value');

        $this->assertEquals('normal', $result);
    }

    /**
     * @test
     */
    function it_can_overwrite_return_value_during_prefix_interception()
    {
        $instance = new ProxyInstance();
        $this->cacheHandler
            ->method('interceptProxyPrefix')
            ->willReturn('prefixvalue');

        /** @var ProxyInstance $proxy */
        $proxy = $this->proxyGenerator->generate($instance);
        $result = $proxy->triggerCache('value');

        $this->assertEquals('prefixvalue', $result);
    }

    /**
     * @test
     */
    function it_can_overwrite_return_value_during_suffix_interception()
    {
        $instance = new ProxyInstance();
        $this->cacheHandler
            ->method('interceptProxySuffix')
            ->willReturn('suffixvalue');

        /** @var ProxyInstance $proxy */
        $proxy = $this->proxyGenerator->generate($instance);
        $result = $proxy->triggerCache('value');

        $this->assertEquals('suffixvalue', $result);
    }

    /**
     * @test
     */
    function it_does_not_interact_with_methods_that_dont_have_caching_annotations()
    {
        $instance = new ProxyInstance();
        $this->cacheHandler->expects($this->never())->method('interceptProxyPrefix');
        $this->cacheHandler->expects($this->never())->method('interceptProxySuffix');

        /** @var ProxyInstance $proxy */
        $proxy = $this->proxyGenerator->generate($instance);
        $result = $proxy->passThrough('value');

        $this->assertEquals('normal', $result);
    }
}
