<?php
declare(strict_types=1);

namespace Phpro\AnnotatedCache\Proxy;

use Doctrine\Common\Annotations\Reader;
use Phpro\AnnotatedCache\Annotation\CacheAnnotation;
use Phpro\AnnotatedCache\Cache\CacheHandlerInterface;
use Phpro\AnnotatedCache\Collection\AnnotationCollection;
use Phpro\AnnotatedCache\Interception\ProxyInterceptionPrefix;
use Phpro\AnnotatedCache\Interception\ProxyInterceptionSuffix;
use ProxyManager\Factory\AccessInterceptorValueHolderFactory;
use ProxyManager\Proxy\AccessInterceptorInterface;
use ReflectionClass;
use ReflectionMethod;

/**
 * Class ProxyGenerator
 *
 * @package Phpro\AnnotatedCache\Proxy
 */
class ProxyGenerator
{
    /**
     * @var AccessInterceptorValueHolderFactory
     */
    private $proxyFactory;

    /**
     * @var Reader
     */
    private $annotationsReader;

    /**
     * @var CacheHandlerInterface
     */
    private $cacheHandler;

    /**
     * ProxyGenerator constructor.
     *
     * @param AccessInterceptorValueHolderFactory $proxyFactory
     * @param Reader                              $annotationsReader
     * @param CacheHandlerInterface               $cacheHandler
     */
    public function __construct(
        AccessInterceptorValueHolderFactory $proxyFactory,
        Reader $annotationsReader,
        CacheHandlerInterface $cacheHandler
    ) {
        $this->proxyFactory = $proxyFactory;
        $this->annotationsReader = $annotationsReader;
        $this->cacheHandler = $cacheHandler;
    }

    /**
     * @param mixed $instance
     *
     * @return AccessInterceptorInterface
     */
    public function generate($instance) : AccessInterceptorInterface
    {
        $class = new ReflectionClass($instance);
        $proxy = $this->proxyFactory->createProxy($instance);

        foreach ($this->collectCacheMethods($class) as $method => $annotations) {
            $this->registerPrefixInterceptor($proxy, $method, $annotations);
            $this->registerSuffixInterceptor($proxy, $method, $annotations);
        }

        return $proxy;
    }

    /**
     * @param ReflectionClass $class
     *
     * @return array
     */
    private function collectCacheMethods(ReflectionClass $class)
    {
        $methods = [];
        foreach ($class->getMethods() as $method) {
            $annotations = $this->collectMethodCacheAnnotations($method);
            if ($annotations->count()) {
                $methods[$method->getName()] = $annotations;
            }
        }

        return $methods;
    }

    /**
     * @param ReflectionMethod $method
     *
     * @return CacheAnnotation[]|AnnotationCollection
     */
    private function collectMethodCacheAnnotations(ReflectionMethod $method)
    {
        $annotations = array_filter($this->annotationsReader->getMethodAnnotations($method), function ($annotation) {
            return $annotation instanceof CacheAnnotation;
        });

        return new AnnotationCollection($annotations);
    }

    /**
     * @param AccessInterceptorInterface $proxy
     * @param string                     $method
     * @param AnnotationCollection       $annotations
     */
    private function registerPrefixInterceptor(
        AccessInterceptorInterface $proxy,
        $method,
        AnnotationCollection $annotations
    ) {
        $cacheHandler = $this->cacheHandler;
        $proxy->setMethodPrefixInterceptor($method, function (
            $proxy,
            $instance,
            $method,
            $params,
            & $returnEarly
        ) use (
            $cacheHandler,
            $annotations
        ) {
            $interception = new ProxyInterceptionPrefix($instance, $method, $params);
            if (!$result = $cacheHandler->interceptProxyPrefix($annotations, $interception)) {
                return null;
            }

            $returnEarly = true;
            return $result;
        });
    }

    /**
     * @param AccessInterceptorInterface $proxy
     * @param string                     $method
     * @param AnnotationCollection       $annotations
     */
    private function registerSuffixInterceptor(
        AccessInterceptorInterface $proxy,
        $method,
        AnnotationCollection $annotations
    ) {
        $cacheHandler = $this->cacheHandler;
        $proxy->setMethodSuffixInterceptor($method, function (
            $proxy,
            $instance,
            $method,
            $params,
            $returnValue,
            & $returnEarly
        ) use (
            $cacheHandler,
            $annotations
        ) {
            $interception = new ProxyInterceptionSuffix($instance, $method, $params, $returnValue);
            if (!$result = $cacheHandler->interceptProxySuffix($annotations, $interception)) {
                return null;
            }

            $returnEarly = true;
            return $result;
        });
    }
}
