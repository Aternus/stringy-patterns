<?php

use Stringy\StringyPatterns as P;

class StringyPatternsTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * Asserts that a variable is of a StringyPatterns instance.
     *
     * @param mixed $actual
     */
    public function assertStringyPatterns($actual)
    {
        $this->assertInstanceOf('Stringy\StringyPatterns', $actual);
    }

    public function testConstruct()
    {
        $stringyPatterns = new P('abab', 'UTF-8');
        $this->assertStringyPatterns($stringyPatterns);
        $this->assertEquals('abab', (string)$stringyPatterns);
        $this->assertEquals('UTF-8', $stringyPatterns->getEncoding());
    }

    public function testEmptyConstruct()
    {
        $stringyPatterns = new P();
        $this->assertStringyPatterns($stringyPatterns);
        $this->assertEquals('', (string)$stringyPatterns);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testConstructWithArray()
    {
        (string)new P([]);
        $this->fail('Expecting exception when the constructor is passed an array');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testMissingToString()
    {
        (string)new P(new stdClass());
        $this->fail('Expecting exception when the constructor is passed an object without a __toString method');
    }

    /**
     * @dataProvider toStringProvider()
     */
    public function testToString($expected, $str)
    {
        $this->assertEquals($expected, (string)new P($str));
    }

    public function toStringProvider()
    {
        return [
            ['', null],
            ['', false],
            ['1', true],
            ['-9', -9],
            ['1.18', 1.18],
            [' string  ', ' string  '],
        ];
    }

    public function testCreate()
    {
        $stringyPatterns = P::create('abab', 'UTF-8');
        $this->assertStringyPatterns($stringyPatterns);
        $this->assertEquals('abab', (string)$stringyPatterns);
        $this->assertEquals('UTF-8', $stringyPatterns->getEncoding());
    }

    public function testGetPatterns()
    {
        $stringyPatterns = new P('abcdabcdab');
        $this->assertStringyPatterns($stringyPatterns);
        $expected = [
            'a'    => 3,
            'b'    => 3,
            'c'    => 2,
            'd'    => 2,
            'ab'   => 3,
            'cd'   => 2,
            'abc'  => 2,
            'dab'  => 2,
            'cda'  => 2,
            'abcd' => 2,
            'cdab' => 2,
            'bc'   => 2,
            'da'   => 2,
            'bcd'  => 2,
            'bcda' => 2,
        ];
        $result = $stringyPatterns->getPatterns();
        $this->assertEquals($expected, $result);
    }

    public function testGetPatternsWithSingles()
    {
        $stringyPatterns = new P('abcdabcdab');
        $this->assertStringyPatterns($stringyPatterns);
        $expected = [
            'a'          => 3,
            'b'          => 3,
            'c'          => 2,
            'd'          => 2,
            'ab'         => 3,
            'cd'         => 2,
            'abc'        => 2,
            'dab'        => 2,
            'cda'        => 2,
            'abcd'       => 2,
            'abcda'      => null,
            'bcdab'      => null,
            'abcdab'     => null,
            'cdab'       => 2,
            'abcdabc'    => null,
            'abcdabcd'   => null,
            'abcdabcda'  => null,
            'abcdabcdab' => null,
            'bc'         => 2,
            'da'         => 2,
            'bcd'        => 2,
            'bcda'       => 2,
            'bcdabc'     => null,
            'bcdabcd'    => null,
            'bcdabcda'   => null,
            'bcdabcdab'  => null,
            'dabc'       => null,
            'dabcd'      => null,
            'dabcda'     => null,
            'dabcdab'    => null,
        ];
        $result = $stringyPatterns->getPatterns(true);
        $this->assertEquals($expected, $result);
    }
}
