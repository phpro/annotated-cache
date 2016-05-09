<?php

namespace PhproTest\AnnotatedCache\Unit\KeyGenerator;


use Phpro\AnnotatedCache\KeyGenerator\KeyGeneratorInterface;
use Phpro\AnnotatedCache\KeyGenerator\SimpleHashKeyGenerator;
use PhproTest\AnnotatedCache\Objects\Foo;
use PhproTest\AnnotatedCache\Objects\TestAnnotation;
use PhproTest\AnnotatedCache\Objects\TestInterception;

class SimpleHashKeyGeneratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SimpleHashKeyGenerator
     */
    private $generator;

    protected function setUp()
    {
        $this->generator = new SimpleHashKeyGenerator();
    }

    /**
     * @test
     */
    function it_is_a_key_generator()
    {
        $this->assertInstanceOf(KeyGeneratorInterface::class, $this->generator);
    }

    /**
     * @test
     */
    public function it_can_handle_a_single_scalar_parameter()
    {
        $interception = new TestInterception(new \stdClass(), 'method', ['foo']);
        $annotation = new TestAnnotation([]);
        
        $expected = sha1('stdClass::method;' . sprintf('0=%s;', sha1('foo')));
        $this->assertEquals($expected, $this->generator->generateKey($interception, $annotation));
    }

    /**
     * @test
     */
    public function it_can_handle_an_array_of_scalar_parameter()
    {
        $interception = new TestInterception(new \stdClass(), 'method', ['foo', 'bar']);
        $annotation = new TestAnnotation([]);

        $expected = sha1(
            'stdClass::method;'
            . sprintf('0=%s;', sha1('foo'))
            . sprintf('1=%s;', sha1('bar'))
        );
        $this->assertEquals($expected, $this->generator->generateKey($interception, $annotation));
    }

    /**
     * @test
     */
    public function it_can_handle_null_parameters()
    {
        $interception = new TestInterception(new \stdClass(), 'method', [null]);
        $annotation = new TestAnnotation([]);

        $expected = sha1('stdClass::method;' . '0=null;');
        $this->assertEquals($expected, $this->generator->generateKey($interception, $annotation));
    }

    /**
     * @test
     */
    public function it_can_handle_simple_array_parameters()
    {
        $parameter = array('foo', 'bar');
        $interception = new TestInterception(new \stdClass(), 'method', [$parameter]);
        $annotation = new TestAnnotation([]);

        $parameter = array('foo', 'bar');
        $expected = sha1('stdClass::method;' . sprintf('0=%s;', sha1(serialize($parameter))));
        $this->assertEquals($expected, $this->generator->generateKey($interception, $annotation));
    }

    /**
     * @test
     */
    public function it_can_handle_mixed_parameters()
    {
        $param1 = new Foo();
        $param2 = array('foo', 'bar');
        $param3 = 'foo';
        $param4 = null;

        $interception = new TestInterception(new \stdClass(), 'method', [$param1, $param2, $param3, $param4]);
        $annotation = new TestAnnotation([]);

        $expected = sha1(
            'stdClass::method;'
            . sprintf('0=%s;', sha1(serialize($param1)))
            . sprintf('1=%s;', sha1(serialize($param2)))
            . sprintf('2=%s;', sha1($param3))
            . '3=null;'
        );
        $this->assertEquals($expected, $this->generator->generateKey($interception, $annotation));
    }

    /**
     * @test
     */
    public function it_create_unique_hashes_for_different_arrays()
    {
        $firstInterception = new TestInterception(new \stdClass(), 'method', ['foo', 'bar', 'baz', 'unicorn']);
        $secondInterception = new TestInterception(new \stdClass(), 'method', ['foo', 'bar', 'baz', 'poney']);
        $annotation = new TestAnnotation([]);

        $firstHash = $this->generator->generateKey($firstInterception, $annotation);
        $secondHash = $this->generator->generateKey($secondInterception, $annotation);

        $this->assertNotEquals($firstHash, $secondHash);
    }
}
