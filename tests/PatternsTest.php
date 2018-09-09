<?php

namespace Stringy\Patterns\Tests;

use PHPUnit\Framework\TestCase;
use Stringy\Patterns as StringyPatterns;

final class PatternsTest extends TestCase
{
    public function assertValidInstance($actual)
    {
        $this->assertInstanceOf(StringyPatterns::class, $actual);
    }

    /**
     * Tests
     *****************************************************/
    public function testIsAValidInstance()
    {
        $stringyPatterns = new StringyPatterns('abab', 'UTF-8');
        $this->assertValidInstance($stringyPatterns);
        $this->assertEquals('abab', (string)$stringyPatterns);
        $this->assertEquals('UTF-8', $stringyPatterns->getEncoding());
    }

    public function testConstructEmpty()
    {
        $stringyPatterns = new StringyPatterns();
        $this->assertValidInstance($stringyPatterns);
        $this->assertEquals('', (string)$stringyPatterns);
    }

    public function testConstructWithArray()
    {
        $this->expectException(\InvalidArgumentException::class);
        (string)new StringyPatterns([]);
        $this->fail('Expecting exception when the constructor is passed an array');
    }

    public function testMissingToStringMethod()
    {
        $this->expectException(\InvalidArgumentException::class);
        (string)new StringyPatterns(new \stdClass());
        $this->fail('Expecting exception when the constructor is passed an object without a __toString method');
    }

    /**
     * @dataProvider providerToString()
     */
    public function testInstanceHasAWorkingToStringMethod($expected, $str)
    {
        $this->assertEquals($expected, (string)new StringyPatterns($str));
    }

    public function testStaticCreate()
    {
        $stringyPatterns = StringyPatterns::create('abab', 'UTF-8');
        $this->assertValidInstance($stringyPatterns);
        $this->assertEquals('abab', (string)$stringyPatterns);
        $this->assertEquals('UTF-8', $stringyPatterns->getEncoding());
    }

    public function testGetPatterns()
    {
        $stringyPatterns = new StringyPatterns('abcdabcdab');
        $this->assertValidInstance($stringyPatterns);
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
        $stringyPatterns = new StringyPatterns('abcdabcdab');
        $this->assertValidInstance($stringyPatterns);
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

    /**
     * Data Providers
     *****************************************************/
    public function providerToString()
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
}
