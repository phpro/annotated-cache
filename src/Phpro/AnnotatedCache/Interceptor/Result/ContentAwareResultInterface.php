<?php
declare(strict_types=1);

namespace Phpro\AnnotatedCache\Interceptor\Result;

/**
 * Class ContentAwareResultInterface
 *
 * @package Phpro\AnnotatedCache\Interceptor\Result
 */
interface ContentAwareResultInterface
{

    /**
     * @return mixed
     */
    public function getContent();

    /**
     * @return bool
     */
    public function hasContent() : bool;
}
