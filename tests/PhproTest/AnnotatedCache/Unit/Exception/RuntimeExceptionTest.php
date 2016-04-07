<?php

namespace PhproTest\AnnotatedCache\Unit\Exception;

use Phpro\AnnotatedCache\Exception\RuntimeException;

class RuntimeExceptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    function it_is_an_exception()
    {
        $this->assertInstanceOf(\Exception::class, new RuntimeException());
    }
}
