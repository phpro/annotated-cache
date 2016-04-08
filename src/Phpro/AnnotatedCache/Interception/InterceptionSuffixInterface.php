<?php
declare(strict_types=1);

namespace Phpro\AnnotatedCache\Interception;

/**
 * Interface InterceptionSufficInterface
 *
 * @package Phpro\AnnotatedCache\Interception
 */
interface InterceptionSuffixInterface extends InterceptionInterface
{
    /**
     * The return value of the intercepted method
     * @return mixed
     */
    public function getReturnValue();
}
