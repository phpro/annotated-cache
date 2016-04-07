<?php

namespace PhproTest\AnnotatedCache\Unit\Cache;

use Phpro\AnnotatedCache\Cache\PoolManager;
use Phpro\AnnotatedCache\Cache\PoolManagerInterface;
use Phpro\AnnotatedCache\Exception\RuntimeException;
use Phpro\AnnotatedCache\Factory;
use Psr\Cache\CacheItemPoolInterface;

/**
 * Class PoolManagerTest
 *
 * @package PhproTest\AnnotatedCache\Unit
 */
class PoolManagerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var PoolManager
     */
    private $poolManager;

    protected function setUp()
    {
        $this->poolManager = Factory::createPoolManager();
    }

    /**
     * @test
     */
    function it_is_a_pool_manager()
    {
        $this->assertInstanceOf(PoolManagerInterface::class, $this->poolManager);
    }

    /**
     * @test
     */
    function it_contains_psr6_cache_pools()
    {
        $pool = $this->getMockBuilder(CacheItemPoolInterface::class)->getMock();
        $this->poolManager->addPool('mypool', $pool);

        $this->assertTrue($this->poolManager->hasPool('mypool'));
        $this->assertEquals($pool, $this->poolManager->getPool('mypool'));
    }

    function it_fails_when_a_pool_does_not_exist()
    {
        $pool = $this->getMockBuilder(CacheItemPoolInterface::class)->getMock();

        $this->assertFalse($this->poolManager->hasPool('mypool'));
        $this->expectException(RuntimeException::class);
        $this->assertEquals($pool, $this->poolManager->getPool('mypool'));
    }
}
