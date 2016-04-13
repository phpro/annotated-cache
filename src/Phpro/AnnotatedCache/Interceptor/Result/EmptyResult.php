<?php
declare(strict_types=1);

namespace Phpro\AnnotatedCache\Interceptor\Result;

/**
 * Class EmptyResult
 *
 * @package Phpro\AnnotatedCache\Interceptor\Result
 */
class EmptyResult implements ResultInterface
{
    /**
     * @return string
     */
    public function getClassName() : string
    {
        return '';
    }

    /**
     * @return string
     */
    public function getMethod() : string
    {
        return '';
    }

    /**
     * @return string
     */
    public function getKey() : string
    {
        return '';
    }

    /**
     * @return array
     */
    public function getPools() : array
    {
        return [];
    }
}
