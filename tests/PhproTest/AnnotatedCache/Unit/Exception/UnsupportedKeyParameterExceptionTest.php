<?php

namespace PhproTest\AnnotatedCache\Unit\Exception;

use Phpro\AnnotatedCache\Exception\UnsupportedKeyParameterException;

class UnsupportedKeyParameterExceptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    function it_is_an_exception()
    {
        $this->assertInstanceOf(\Exception::class, new UnsupportedKeyParameterException());
    }
}
