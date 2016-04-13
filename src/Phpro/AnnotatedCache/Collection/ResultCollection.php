<?php

namespace Phpro\AnnotatedCache\Collection;

use Doctrine\Common\Collections\ArrayCollection;
use Phpro\AnnotatedCache\Interceptor\Result\HittableResultInterface;
use Phpro\AnnotatedCache\Interceptor\Result\ResultInterface;

/**
 * Class ResultCollection
 *
 * @package Phpro\AnnotatedCache\Collection
 */
class ResultCollection extends ArrayCollection
{
    /**
     * @return int
     */
    public function countHits()
    {
        $counter = 0;
        foreach ($this->filterByType(HittableResultInterface::class) as $result) {
            $counter += (int) $result->isCacheHit();
        }

        return $counter;
    }

    /**
     * @return int
     */
    public function countMisses()
    {
        $counter = 0;
        foreach ($this->filterByType(HittableResultInterface::class) as $result) {
            $counter += (int) !$result->isCacheHit();
        }

        return $counter;
    }

    /**
     * @param $classNameOrInterface
     *
     * @return ResultCollection
     */
    public function filterByType($classNameOrInterface)
    {
        return $this->filter(function (ResultInterface $result) use ($classNameOrInterface) {
            return $result instanceof $classNameOrInterface;
        });
    }
}
