<?php

namespace PhproTest\AnnotatedCache\Unit\Interceptor\Result;

use Phpro\AnnotatedCache\Interception\InterceptionInterface;
use Phpro\AnnotatedCache\Interceptor\Result\ContentAwareResultInterface;
use Phpro\AnnotatedCache\Interceptor\Result\UpdateResult;
use Phpro\AnnotatedCache\Interceptor\Result\HittableResultInterface;
use PhproTest\AnnotatedCache\Objects\BookService;

/**
 * Class UpdateResultTest
 *
 * @package PhproTest\AnnotatedCache\Unit\Interceptor\Result
 */
class UpdateResultTest extends AbstractResultTest
{

    /**
     * @var UpdateResult
     */
    protected $result;

    protected function setUp()
    {
        parent::setUp();

        $this->result = new UpdateResult($this->interception, 'key', ['pool']);
    }
}
