<?php

namespace Phpro\AnnotatedCache;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\Reader;
use Phpro\AnnotatedCache\Cache\CacheHandler;
use Phpro\AnnotatedCache\Cache\PoolManager;
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
    public static function createKeyGenerator()
    {
        return new ExpressionGenerator(new SimpleHashKeyGenerator());
    }

    /**
     * @return PoolManager
     */
    public static function createPoolManager()
    {
        return new PoolManager();
    }

    /**
     * @param PoolManager                $poolManager
     * @param KeyGeneratorInterface|null $keyGenerator
     *
     * @return CacheHandler
     */
    public static function createCacheHandler(PoolManager $poolManager, KeyGeneratorInterface $keyGenerator = null)
    {
        $keyGenerator = $keyGenerator ?? self::createKeyGenerator();

        $cacheHandler = new CacheHandler();
        $cacheHandler->addInterceptor(new Interceptor\CacheableInterceptor($poolManager, $keyGenerator));
        $cacheHandler->addInterceptor(new Interceptor\CacheEvictInterceptor($poolManager, $keyGenerator));
        $cacheHandler->addInterceptor(new Interceptor\CacheableInterceptor($poolManager, $keyGenerator));

        return $cacheHandler;
    }

    /**
     * @param CacheHandler       $cacheHandler
     * @param Configuration|null $proxyConfig
     * @param Reader|null        $annotationReader
     *
     * @return ProxyGenerator
     */
    public static function createProxyGenerator(
        CacheHandler $cacheHandler,
        Configuration $proxyConfig = null,
        Reader $annotationReader = null
    ) {
        $proxyConfig = $proxyConfig ?? new Configuration();
        $annotationReader = $annotationReader ?? new AnnotationReader();
        $proxyFactory = new AccessInterceptorValueHolderFactory($proxyConfig);

        return new ProxyGenerator($proxyFactory, $annotationReader, $cacheHandler);
    }
}
