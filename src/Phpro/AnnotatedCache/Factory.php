<?php
declare(strict_types=1);

namespace Phpro\AnnotatedCache;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\Reader;
use Phpro\AnnotatedCache\Cache\CacheHandler;
use Phpro\AnnotatedCache\Cache\CacheHandlerInterface;
use Phpro\AnnotatedCache\Cache\PoolManager;
use Phpro\AnnotatedCache\Collector\MemoryResultCollector;
use Phpro\AnnotatedCache\Collector\NullResultCollector;
use Phpro\AnnotatedCache\Collector\ResultCollectorInterface;
use Phpro\AnnotatedCache\KeyGenerator\ExpressionGenerator;
use Phpro\AnnotatedCache\KeyGenerator\KeyGeneratorInterface;
use Phpro\AnnotatedCache\KeyGenerator\SimpleHashKeyGenerator;
use Phpro\AnnotatedCache\Proxy\ProxyGenerator;
use ProxyManager\Configuration;
use ProxyManager\Factory\AccessInterceptorValueHolderFactory;

/**
 * Class Factory
 *
 * @package Phpro\AnnotatedCache
 */
class Factory
{
    /**
     * @return ExpressionGenerator
     */
    public static function createKeyGenerator() : ExpressionGenerator
    {
        return new ExpressionGenerator(new SimpleHashKeyGenerator());
    }

    /**
     * @return PoolManager
     */
    public static function createPoolManager() : PoolManager
    {
        return new PoolManager();
    }

    /**
     * @param PoolManager                $poolManager
     * @param KeyGeneratorInterface|null $keyGenerator
     * @param ResultCollectorInterface   $resultCollector
     *
     * @return CacheHandler
     */
    public static function createCacheHandler(
        PoolManager $poolManager,
        KeyGeneratorInterface $keyGenerator = null,
        ResultCollectorInterface $resultCollector = null
    ) : CacheHandler {
        $keyGenerator = $keyGenerator ?? self::createKeyGenerator();
        $resultCollector = $resultCollector ?? new NullResultCollector();

        $cacheHandler = new CacheHandler($resultCollector);
        $cacheHandler->addInterceptor(new Interceptor\CacheableInterceptor($poolManager, $keyGenerator));
        $cacheHandler->addInterceptor(new Interceptor\CacheUpdateInterceptor($poolManager, $keyGenerator));
        $cacheHandler->addInterceptor(new Interceptor\CacheEvictInterceptor($poolManager, $keyGenerator));

        return $cacheHandler;
    }

    /**
     * @param CacheHandlerInterface $cacheHandler
     * @param Configuration|null    $proxyConfig
     * @param Reader|null           $annotationReader
     *
     * @return ProxyGenerator
     */
    public static function createProxyGenerator(
        CacheHandlerInterface $cacheHandler,
        Configuration $proxyConfig = null,
        Reader $annotationReader = null
    ) : ProxyGenerator {
        $proxyConfig = $proxyConfig ?? new Configuration();
        $annotationReader = $annotationReader ?? new AnnotationReader();
        $proxyFactory = new AccessInterceptorValueHolderFactory($proxyConfig);

        return new ProxyGenerator($proxyFactory, $annotationReader, $cacheHandler);
    }
}
