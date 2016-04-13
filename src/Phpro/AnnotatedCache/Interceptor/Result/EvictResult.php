<?php
declare(strict_types=1);

namespace Phpro\AnnotatedCache\Interceptor\Result;

use Phpro\AnnotatedCache\Interception\InterceptionInterface;

/**
 * Class EvictResult
 *
 * @package Phpro\AnnotatedCache\Interceptor\Result
 */
final class EvictResult extends AbstractResult implements TagsAwareResultInterface
{
    /**
     * @var array
     */
    private $tags = [];

    /**
     * EvictResult constructor.
     *
     * @param InterceptionInterface $interception
     * @param string                $key
     * @param array                 $pools
     * @param array                 $tags
     */
    public function __construct(InterceptionInterface $interception, $key, array $pools, array $tags)
    {
        parent::__construct($interception, $key, $pools, $tags);
        $this->tags = $tags;
    }

    /**
     * @return array
     */
    public function getTags() : array
    {
        return $this->tags;
    }
}
