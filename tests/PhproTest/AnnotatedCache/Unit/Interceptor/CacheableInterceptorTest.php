<?php

namespace PhproTest\AnnotatedCache\Unit\Interceptor;

use Cache\Adapter\Common\CacheItem;
use Cache\Taggable\TaggablePoolInterface;
use Phpro\AnnotatedCache\Annotation\Cacheable;
use Phpro\AnnotatedCache\Cache\PoolManager;
use Phpro\AnnotatedCache\Cache\PoolManagerInterface;
use Phpro\AnnotatedCache\Interception\ProxyInterceptionPrefix;
use Phpro\AnnotatedCache\Interception\ProxyInterceptionSuffix;
use Phpro\AnnotatedCache\Interceptor\CacheableInterceptor;
use Phpro\AnnotatedCache\Interceptor\InterceptorInterface;
use Phpro\AnnotatedCache\KeyGenerator\KeyGeneratorInterface;

class CacheableInterceptorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var PoolManagerInterface
     */
    private $poolManager;

    /**
     * @var TaggablePoolInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $pool;

    /**
     * @var KeyGeneratorInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $keyGenerator;

    /**
     * @var CacheableInterceptor
     */
    private $interceptor;

    protected function setUp()
    {
        $this->poolManager = new PoolManager();
        $this->pool = $this->getMockBuilder(TaggablePoolInterface::class)->getMock();
        $this->keyGenerator = $this->getMockBuilder(KeyGeneratorInterface::class)->getMock();
        $this->keyGenerator->method('generateKey')->willReturn('key');

        $this->poolManager->addPool('pool', $this->pool);

        $this->interceptor = new CacheableInterceptor($this->poolManager, $this->keyGenerator);
    }

    /**
     * @test
     */
    function it_is_an_interceptor()
    {
        $this->assertInstanceOf(InterceptorInterface::class, $this->interceptor);
    }

    /**
     * @test
     */
    function it_supports_cacheable_annotation()
    {
        $this->assertTrue($this->interceptor->canInterceptAnnotation(new Cacheable(['pools' => 'pool'])));
    }

    /**
     * @test
     */
    function it_loads_result_from_cache()
    {
        $item = (new CacheItem('key'))->set('value');
        $this->pool->method('hasItem')->with('key')->willReturn(true);
        $this->pool->method('getItem')->with('key')->willReturn($item);

        $annotation = new Cacheable(['pools' => 'pool']);
        $interception = new ProxyInterceptionPrefix(new \stdClass(), 'method', ['key1' => 'value1']);
        $result = $this->interceptor->interceptPrefix($annotation, $interception);

        $this->assertEquals('value', $result);
    }

    /**
     * @test
     */
    function it_doesnt_load_result_if_cache_does_not_exist()
    {
        $this->pool->method('hasItem')->with('key')->willReturn(false);

        $annotation = new Cacheable(['pools' => 'pool']);
        $interception = new ProxyInterceptionPrefix(new \stdClass(), 'method', ['key1' => 'value1']);
        $result = $this->interceptor->interceptPrefix($annotation, $interception);

        $this->assertNull($result);
    }

    /**
     * @test
     */
    function it_doesnt_load_result_if_cache_is_expired()
    {
        $item = (new CacheItem('key'))->set('value')->expiresAt(new \DateTime('now - 1 hour'));
        $this->pool->method('hasItem')->with('key')->willReturn(false);
        $this->pool->method('getItem')->with('key')->willReturn($item);

        $annotation = new Cacheable(['pools' => 'pool']);
        $interception = new ProxyInterceptionPrefix(new \stdClass(), 'method', ['key1' => 'value1']);
        $result = $this->interceptor->interceptPrefix($annotation, $interception);

        $this->assertNull($result);
    }

    /**
     * @test
     */
    function it_saves_result_to_cache()
    {
        $this->pool
            ->expects($this->once())
            ->method('save')
            ->with($this->callback(function(CacheItem $item) {
                return $item->getKey() === 'key'
                    && $item->get() === 'value'
                    && $item->getTags() === ['tag']
                    && $item->getExpirationDate() instanceof \DateTime;
            }));

        $annotation = new Cacheable(['pools' => 'pool', 'tags' => 'tag', 'ttl' => 300]);
        $interception = new ProxyInterceptionSuffix(new \stdClass(), 'method', ['key1' => 'value1'], 'value');
        $result = $this->interceptor->interceptSuffix($annotation, $interception);

        $this->assertNull($result);
    }

    /**
     * @test
     */
    function it_does_not_save_empty_result_to_cache()
    {
        $this->pool->expects($this->never())->method('save');

        $annotation = new Cacheable(['pools' => 'pool', 'tags' => 'tag', 'ttl' => 300]);
        $interception = new ProxyInterceptionSuffix(new \stdClass(), 'method', ['key1' => 'value1'], null);
        $result = $this->interceptor->interceptSuffix($annotation, $interception);

        $this->assertNull($result);
    }
}
