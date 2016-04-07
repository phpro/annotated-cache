# Cache Annotations

.... PSR 6 .... ANNOTATIONS ....


## Installation

```sh
composer require phpro/annotated-cache
```

We suggest using php-cache to make it possible to add tags to your cache items.


## Usage

```php
use Phpro\AnnotatedCache\Factory;

$poolManager = Factory::createPoolManager();
$poolManager->addPool('products', $psr6CachePool);

$cacheHandler = Factory::createCacheHandler($poolManager);
$proxyGenerator = Factory::createProxyGenerator($poolManager);

$myService = $proxyGenerator->generate($myService);
```

```php
<?php

namespace My;

use Phpro\AnnotatedCache\Annotation\Cacheable;
use Phpro\AnnotatedCache\Annotation\CacheUpdate;
use Phpro\AnnotatedCache\Annotation\CacheEvict;

class Service
{

    /**
     * @Cacheable(pools="products", key="sku", tags="product-detail")
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
     * @CacheUpdate(pools="products", key="product.getSku()", tags="product-detail")
     */
    public function updateProduct(Product $product)
    {
        // saving product....
        return $product;
    }
}
```