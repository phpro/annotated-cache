<?php
declare(strict_types=1);

namespace Phpro\AnnotatedCache\Interceptor\Result;

/**
 * Class AbstractResult
 *
 * @package Phpro\AnnotatedCache\Interceptor\Result
 */
interface ResultInterface
{
    /**
     * @return string
     */
    public function getClassName() : string;

    /**
     * @return string
     */
    public function getMethod() : string;

    /**
     * @return string
     */
    public function getKey() : string;

    /**
     * @return array|\string[]
     */
    public function getPools() : array;
}
