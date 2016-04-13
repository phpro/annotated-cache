<?php
declare(strict_types=1);

namespace Phpro\AnnotatedCache\Interceptor\Result;

use Phpro\AnnotatedCache\Interception\InterceptionInterface;

/**
 * Class HitResult
 *
 * @package Phpro\AnnotatedCache\Interceptor\Result
 */
final class HitResult extends AbstractResult implements HittableResultInterface, ContentAwareResultInterface
{
    /**
     * @var mixed|null
     */
    private $content = null;

    /**
     * HitResult constructor.
     *
     * @param InterceptionInterface $interception
     * @param string                $key
     * @param array                 $pools
     * @param mixed                 $content
     */
    public function __construct(InterceptionInterface $interception, $key, array $pools, $content)
    {
        parent::__construct($interception, $key, $pools);
        $this->content = $content;
    }

    /**
     * @return bool
     */
    public function isCacheHit() : bool
    {
        return true;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return bool
     */
    public function hasContent() : bool
    {
        return null !== $this->content;
    }
}
