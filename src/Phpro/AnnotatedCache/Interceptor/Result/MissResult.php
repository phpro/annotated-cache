<?php
declare(strict_types=1);

namespace Phpro\AnnotatedCache\Interceptor\Result;

/**
 * Class MissResult
 *
 * @package Phpro\AnnotatedCache\Interceptor\Result
 */
final class MissResult extends AbstractResult implements HittableResultInterface
{
    /**
     * @return bool
     */
    public function isCacheHit() : bool
    {
        return false;
    }
}
