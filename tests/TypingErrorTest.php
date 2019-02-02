<?php
/**
 * @package axy\errors
 */

declare(strict_types=1);

namespace axy\errors\tests;

use PHPUnit\Framework\TestCase;
use axy\errors\TypingError;

/**
 * coversDefaultClass axy\errors\TypingError
 */
class TypingErrorTest extends TestCase
{
    /**
     * covers ::__construct
     * covers ::getVarname
     * covers ::getExpected
     */
    public function testNormal()
    {
        $previous = new \RuntimeException('msg');
        $e = new TypingError('var', 'array', $previous);
        $this->assertSame('var', $e->getVarname());
        $this->assertSame(['array'], $e->getExpected());
        $this->assertSame($previous, $e->getPrevious());
        $this->assertSame('var must be array', $e->getMessage());
    }

    /**
     * covers ::__construct
     * covers ::getVarname
     * covers ::getExpected
     */
    public function testExpectedArray()
    {
        $e = new TypingError('arg', ['MyClass', 'array', 'string']);
        $this->assertSame('arg', $e->getVarname());
        $this->assertSame(['MyClass', 'array', 'string'], $e->getExpected());
        $this->assertSame('arg must be MyClass, array or string', $e->getMessage());
    }

    /**
     * covers ::__construct
     * covers ::getVarname
     * covers ::getExpected
     */
    public function testExpectedArraySingle()
    {
        $e = new TypingError('arg', ['MyClass']);
        $this->assertSame('arg', $e->getVarname());
        $this->assertSame(['MyClass'], $e->getExpected());
        $this->assertSame('arg must be MyClass', $e->getMessage());
    }

    /**
     * covers ::__construct
     * covers ::getVarname
     * covers ::getExpected
     */
    public function testExpectedArrayEmpty()
    {
        $e = new TypingError('arg', []);
        $this->assertSame('arg', $e->getVarname());
        $this->assertSame([], $e->getExpected());
        $this->assertSame('arg must be a different type', $e->getMessage());
    }


    /**
     * covers ::__construct
     * covers ::getVarname
     * covers ::getExpected
     */
    public function testDefault()
    {
        $e = new TypingError();
        $this->assertNull($e->getVarname());
        $this->assertSame([], $e->getExpected());
        $this->assertSame('A value must be a different type', $e->getMessage());
    }
}
