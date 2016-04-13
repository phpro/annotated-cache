<?php
declare(strict_types=1);

namespace Phpro\AnnotatedCache\Interceptor\Result;

/**
 * Interface TagsAwareResultInterface
 *
 * @package Phpro\AnnotatedCache\Interceptor\Result
 */
interface TagsAwareResultInterface
{
    /**
     * @return array|string[]
     */
    public function getTags() : array;
}
