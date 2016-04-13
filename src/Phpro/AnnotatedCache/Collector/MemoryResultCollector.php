<?php

namespace Phpro\AnnotatedCache\Collector;

use Phpro\AnnotatedCache\Collection\ResultCollection;
use Phpro\AnnotatedCache\Interceptor\Result\EmptyResult;
use Phpro\AnnotatedCache\Interceptor\Result\ResultInterface;

/**
 * Class InMemoryResultCollector
 *
 * @package Phpro\AnnotatedCache\Collector
 */
class MemoryResultCollector implements ResultCollectorInterface
{
    /**
     * @var ResultCollection
     */
    private $results;

    /**
     * ResultCollector constructor.
     */
    public function __construct()
    {
        $this->results = new ResultCollection();
    }

    /**
     * @param ResultInterface $result
     */
    public function collect(ResultInterface $result)
    {
        if ($result instanceof EmptyResult) {
            return;
        }

        $this->results->add($result);
    }

    /**
     * @return ResultCollection
     */
    public function getResults() : ResultCollection
    {
        return $this->results;
    }
}
