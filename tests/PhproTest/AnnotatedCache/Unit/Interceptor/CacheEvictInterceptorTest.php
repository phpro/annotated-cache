<?php

namespace PhproTest\AnnotatedCache\Unit\Interceptor;

use Cache\Taggable\TaggablePoolInterface;
use Phpro\AnnotatedCache\Annotation\CacheEvict;
use Phpro\AnnotatedCache\Cache\PoolManager;
use Phpro\AnnotatedCache\Cache\PoolManagerInterface;
use Phpro\AnnotatedCache\Interception\ProxyInterceptionPrefix;
use Phpro\AnnotatedCache\Interception\ProxyInterceptionSuffix;
use Phpro\AnnotatedCache\Interceptor\CacheEvictInterceptor;
use Phpro\AnnotatedCache\Interceptor\InterceptorInterface;
use Phpro\AnnotatedCache\Interceptor\Result\EmptyResult;
use Phpro\AnnotatedCache\Interceptor\Result\EvictResult;
use Phpro\AnnotatedCache\KeyGenerator\KeyGeneratorInterface;

class CacheEvictInterceptorTest extends \PHPUnit_Framework_TestCase
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
     * @var CacheEvictInterceptor
     */
    private $interceptor;

    protected function setUp()
    {
        $this->poolManager = new PoolManager();
        $this->pool = $this->getMockBuilder(TaggablePoolInterface::class)->getMock();
        $this->keyGenerator = $this->getMockBuilder(KeyGeneratorInterface::class)->getMock();
        $this->keyGenerator->method('generateKey')->willReturn('key');

        $this->poolManager->addPool('pool', $this->pool);

        $this->interceptor = new CacheEvictInterceptor($this->poolManager, $this->keyGenerator);
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
        $this->assertTrue($this->interceptor->canInterceptAnnotation(new CacheEvict(['pools' => 'pool'])));
    }

    /**
     * @test
     */
    function it_does_nothing_on_intercept_prefix()
    {
        $annotation = new CacheEvict(['pools' => 'pool']);
        $interception = new ProxyInterceptionPrefix(new \stdClass(), 'method', ['key1' => 'value1']);
        $result = $this->interceptor->interceptPrefix($annotation, $interception);

        $this->assertInstanceOf(EmptyResult::class, $result);
    }

    /**
     * @test
     */
    function it_removes_a_cache_key_on_inctercept_suffix()
    {
        $this->pool->method('hasItem')->with('key')->willReturn(true);
        $this->pool->expects($this->once())->method('deleteItem')->with('key');

        $annotation = new CacheEvict(['pools' => 'pool', 'key' => 'key']);
        $interception = new ProxyInterceptionSuffix(new \stdClass(), 'method', ['key1' => 'value1'], 'returnValue');
        $result = $this->interceptor->interceptSuffix($annotation, $interception);

        $this->assertInstanceOf(EvictResult::class, $result);
    }

    /**
     * @test
     */
    function it_removes_a_cache_tags_on_inctercept_suffix()
    {
        $this->pool->method('hasItem')->with('key')->willReturn(true);
        $this->pool->expects($this->once())->method('clearTags')->with(['tag1', 'tag2']);

        $annotation = new CacheEvict(['pools' => 'pool', 'tags' => 'tag1, tag2']);
        $interception = new ProxyInterceptionSuffix(new \stdClass(), 'method', ['key1' => 'value1'], 'returnValue');
        $result = $this->interceptor->interceptSuffix($annotation, $interception);

        $this->assertInstanceOf(EvictResult::class, $result);
    }
}
