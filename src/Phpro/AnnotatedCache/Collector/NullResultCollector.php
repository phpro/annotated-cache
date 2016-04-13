<?php

namespace Phpro\AnnotatedCache\Collector;

use Phpro\AnnotatedCache\Collection\ResultCollection;
use Phpro\AnnotatedCache\Interceptor\Result\ResultInterface;

/**
 * Class NullResultCollector
 *
 * @package Phpro\AnnotatedCache\Collector
 */
class NullResultCollector implements ResultCollectorInterface
{
    /**
     * @param ResultInterface $result
     */
    public function collect(ResultInterface $result)
    {
        // Void
    }

    /**
     * @return ResultCollection
     */
    public function getResults() : ResultCollection
    {
        return new ResultCollection();
    }
}
