<?php
namespace Phpro\AnnotatedCache\Collector;

use Phpro\AnnotatedCache\Collection\ResultCollection;
use Phpro\AnnotatedCache\Interceptor\Result\ResultInterface;

/**
 * Class ResultCollector
 *
 * @package Phpro\AnnotatedCache\Collector
 */
interface ResultCollectorInterface
{
    /**
     * @param ResultInterface $result
     */
    public function collect(ResultInterface $result);

    /**
     * @return ResultCollection
     */
    public function getResults() : ResultCollection;
}
