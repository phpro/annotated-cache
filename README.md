[![Build status](https://api.travis-ci.org/phpro/annotated-cache.svg)](http://travis-ci.org/phpro/annotated-cache)
[![Insight](https://img.shields.io/sensiolabs/i/a270b460-11f9-482e-851f-abf37b48fec1.svg)](https://insight.sensiolabs.com/projects/a270b460-11f9-482e-851f-abf37b48fec1)
[![Installs](https://img.shields.io/packagist/dt/phpro/annotated-cache.svg)](https://packagist.org/packages/phpro/annotated-cache/stats)
[![Packagist](https://img.shields.io/packagist/v/phpro/annotated-cache.svg)](https://packagist.org/packages/phpro/annotated-cache)

# Cache Annotations

Stop worrying about caching, use annotations instead. 
 This PHP library makes it possible to add annotations to your services and handles caching for you.
 You can use any [PSR-6](http://www.php-fig.org/psr/psr-6/) caching implementation as a caching back-end.
 
## Installation

```sh
composer require phpro/annotated-cache
```

We suggest using [php-cache](http://www.php-cache.com/en/latest/) to make it possible to add tags to your cache items.

## Bridges

- [Symfony](https://github.com/phpro/annotated-cache-bundle)

## Usage

### Example usage:
```php
use Phpro\AnnotatedCache\Factory;

$poolManager = Factory::createPoolManager();
$poolManager->addPool('products', $psr6CachePool);

$cacheHandler = Factory::createCacheHandler($poolManager);
$proxyGenerator = Factory::createProxyGenerator($poolManager);

$myService = $proxyGenerator->generate(new My\Service());
```

We made it as easy as possible to get started with the cache manager. You will need 3 services:

- `PoolManager`: contains one or multiple PSR-6 cache pools.
- `CacheHandler`: contains the PoolManager and the logic for interacting with the cache pool.
- `ProxyGenerator`: wraps your service with an [Access Interceptor Value Holder](https://ocramius.github.io/ProxyManager/docs/access-interceptor-value-holder.html)


### Example Service
```php
<?php

namespace My;

use Phpro\AnnotatedCache\Annotation\Cacheable;
use Phpro\AnnotatedCache\Annotation\CacheUpdate;
use Phpro\AnnotatedCache\Annotation\CacheEvict;

class Service
{

    /**
     * @Cacheable(pools="products", key="sku", tags="product-detail", ttl=300)
     */
    public function getProduct($sku, $type = 'book')
    {
        // fetch a product from a repository or whatever
        $product = $this->productRepository->getByType($sku, 'book');

        return $product;
    }

    /**
     * @CacheEvict(pools="products", key="product.getSku()", tags="product-overview,product-reports")
     */
    public function removeProduct(Product $product)
    {
        // saving product ...
    }

    /**
     * @CacheUpdate(pools="products", key="product.getSku()", tags="product-detail", ttl=300)
     */
    public function updateProduct(Product $product)
    {
        // saving product....
        return $product;
    }
}
```

### Annotations

The bundle provides the following annotations:
* [@Cacheable](#cacheable-annotation)
* [@CacheEvict](#cacheevict-annotation)
* [@CacheUpdate](#cacheupdate-annotation)

#### @Cacheable annotation

@Cacheable annotation is used to automatically store the result of a method into the cache.

When a method demarcated with the @Cacheable annotation is called, the bundle checks if an entry exists in the cache
before executing the method. If it finds one, the cache result is returned without having to actually execute the method.

If no cache entry is found, the method is executed and the bundle automatically stores its result into the cache.

```PHP
<?php

namespace My\Manager;

use My\Model\Product;

use Phpro\AnnotatedCache\Annotation\Cacheable;

class ProductManager
{
    /**
     * @Cacheable(pools="products", key="sku", tags="book-detail", ttl=500)
     */
    public function getProduct($sku, $type = 'book')
    {
        // fetch a product from a repository or whatever
        $product = $this->productRepository->getByType($sku, 'book');

        return $product;
    }
}
```

#### @CacheEvict annotation

@CacheEvict annotation allows methods to trigger cache population or cache eviction.

When a method is demarcated with @CacheEvict annotation, the bundle will execute the method and then will automatically
try to delete the cache entry with the provided key and the provided tags.

```PHP
<?php

namespace My\Manager;

use My\Model\Product;

use Phpro\AnnotatedCache\Annotation\CacheEvict;

class ProductManager
{
    /**
     * @CacheEvict(pools="products", key="product.getSku()", tags="book-list")
     */
    public function removeProduct(Product $product)
    {
        // saving product ...
    }
}
```

#### @CacheUpdate annotation

@CacheUpdate annotation is useful for cases where the cache needs to be updated without interfering with the method
execution.

When a method is demarcated with @CacheUpdate annotation, the bundle will always execute the method and then will
automatically try to update the cache entry with the method result.

```php
<?php

namespace My\Manager;

use My\Model\Product;

use Phpro\AnnotatedCache\Annotation\CacheUpdate;

class ProductManager
{
    /**
     * @CacheUpdate(pools="products", key="product.getSku()", tags="product-detail", ttl=300)
     */
    public function updateProduct(Product $product)
    {
        // saving product....

        return $product;
    }
}
```

#### Expression Language

For key generation, [Symfony Expression Language](http://symfony.com/doc/current/components/expression_language/index.html) can be used.

```php
/**
 * @CacheUpdate(pools="products", key="product.getSku()")
 */
 public function updateProduct(Product $product)
 {
    // do something
 }
 ```

The Expression Language allow you to retrieve any arguments passed to your method and use it to generate the cache key.

#### Tags

It is possible to add one or multiple tags to a caching entry.
 Since this is not a default feature in PSR-6, you will have to implement the 
 [cache/taggable-cache](http://www.php-cache.com/en/latest/tagging/)
 `TaggablePoolInterface` on your cache pool.


### Handling Results

It is possible to add a `ResultCollectorInterface` to the `CacheHandler`.
 This way you can provide your application with feedback about what happened with the annotations.
 By default, a dummy collector will be used that doesn't collect anything.
 
```php

use Phpro\AnnotatedCache\Factory;
use Phpro\AnnotatedCache\Collector\MemoryResultCollector;

// Instantiate pool

$resultCollector = new MemoryResultCollector();
$cacheHandler = Factory::createCacheHandler($poolManager, $resultCollector);

// Instantiate proxy service ...

$myService->fetchSomethingCached();
$results = $resultCollector->getResults();

```

The results will be an instance of the `ResultCollection` which will contain a series of `ResultInterface` objects.
Possible results:

- EmptyResult (These once are filtered out by the collector)
- HitResult
- MissResult
- EvictResult
- UpdateResult

### Writing your own annotations

It is pretty easy to write your own annotations and let the `CacheHandler` intercept your own annotation.
The only thing you will need to do is write your own 
implementation of the `InterceptorInterface` and a custom annotation.
Next you can register this custom interceptor on the cache handler and do your own thing.

```php
$cacheHandler = Factory::createCacheHandler($poolManager);
$cacheHandler->addInterceptor($myCustomInterceptor)
```


## About

### Submitting bugs and feature requests

Bugs and feature request are tracked on [GitHub](https://github.com/phpro/annotated-cache/issues).
Please take a look at our rules before [contributing your code](CONTRIBUTING.md).

### License

Annotated-cache is licensed under the MIT License - see the [LICENSE](LICENSE.md) file for details

## Credits
This package is based on the [TbbcCacheBundle](https://github.com/TheBigBrainsCompany/TbbcCacheBundle). 
 It uses exactly the same annotations. 
 The big difference is that this package can be used in any PHP application.

Big ups to the 
 [Doctrine](http://www.doctrine-project.org),
 [proxy-manager](http://ocramius.github.io/ProxyManager/) and 
 [php-cache](http://www.php-cache.com/en/latest/) project for providing the tools that this package needs!
