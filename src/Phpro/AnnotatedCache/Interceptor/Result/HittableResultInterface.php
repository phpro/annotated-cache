<?php
declare(strict_types=1);

namespace Phpro\AnnotatedCache\Interceptor\Result;

/**
 * Interface HittableResultInterface
 *
 * @package Phpro\AnnotatedCache\Interceptor\Result
 */
interface HittableResultInterface
{

    /**
     * @return bool
     */
    public function isCacheHit() : bool;
}
