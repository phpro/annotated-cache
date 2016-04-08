<?php

namespace PhproTest\AnnotatedCache\Unit\KeyGenerator;


use Phpro\AnnotatedCache\KeyGenerator\KeyGeneratorInterface;
use Phpro\AnnotatedCache\KeyGenerator\SimpleHashKeyGenerator;
use PhproTest\AnnotatedCache\Objects\Foo;

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
        $expected = sha1(1234 . sprintf('0=%s;', sha1('foo')));
        $this->assertEquals($expected, $this->generator->generateKey(array('foo')));
    }

    /**
     * @test
     */
    public function it_can_handle_an_array_of_scalar_parameter()
    {
        $expected = sha1(
            1234
            . sprintf('0=%s;', sha1('foo'))
            . sprintf('1=%s;', sha1('bar'))
        );
        $this->assertEquals($expected, $this->generator->generateKey(array('foo', 'bar')));
    }

    /**
     * @test
     */
    public function it_can_handle_null_parameters()
    {
        $expected = sha1(1234 . '0=5678;');
        $this->assertEquals($expected, $this->generator->generateKey([null]));
    }

    /**
     * @test
     */
    public function it_can_handle_simple_array_parameters()
    {
        $parameter = array('foo', 'bar');
        $expected = sha1(1234 . sprintf('0=%s;', sha1(serialize($parameter))));
        $this->assertEquals($expected, $this->generator->generateKey(array($parameter)));
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
        $expected = sha1(
            1234
            . sprintf('0=%s;', sha1(serialize($param1)))
            . sprintf('1=%s;', sha1(serialize($param2)))
            . sprintf('2=%s;', sha1($param3))
            . '3=5678;'
        );
        $this->assertEquals($expected, $this->generator->generateKey(array(
            $param1,
            $param2,
            $param3,
            $param4
        )));
    }

    /**
     * @test
     */
    public function it_create_unique_hashes_for_different_arrays()
    {
        $firstHash = $this->generator->generateKey(array('foo', 'bar', 'baz', 'unicorn'));
        $secondHash = $this->generator->generateKey(array('foo', 'bar', 'baz', 'poney'));
        $this->assertNotEquals($firstHash, $secondHash);
    }
}
