<?php

namespace PhproTest\AnnotatedCache\Objects;

use Phpro\AnnotatedCache\Annotation\Cacheable;

class ProxyInstance
{
    /**
     * @Cacheable(pools="pool")
     */
    public function triggerCache($var)
    {
        return 'normal';
    }

    public function passThrough()
    {
        return 'normal';
    }
}
