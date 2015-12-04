<?php
/**
 * @package axy\errors
 */

namespace axy\errors\tests\helpers;

use axy\errors\InvalidConfig;
use axy\errors\tests\tst\errors\InvalidConfig as CustomInvalidConfig;
use axy\errors\tests\tst\errors\Pointless;
use axy\errors\tests\tst\errors\Truncated;
use axy\errors\tests\tst\Invalid;
use axy\errors\tests\tst\Container;
use axy\errors\tests\tst\ContextTruncated;

/**
 * coversDefaultClass axy\errors\helpers\TraceTruncate
 */
class TraceTruncateTest extends \PHPUnit_Framework_TestCase
{
    /**
     * test*() methods invoked via Reflection (has not key "file")
     *
     * @param boolean $inherit
     * @return \axy\errors\tests\tst\ContextTruncated
     */
    private function getErrorContext($inherit)
    {
        $obj = new Invalid($inherit);
        $line = null;
        $e = null;
        try {
            $line = __LINE__ + 1;
            $obj->begin(1);
            $this->fail('not thrown');
        } catch (InvalidConfig $e) {
        }
        $context = new ContextTruncated();
        $context->obj = $obj;
        $context->line = $line;
        $context->file = __FILE__;
        $context->e = $e;
        return $context;
    }

    public function testCustomNS()
    {
        $context = $this->getErrorContext(true);
        $e = $context->e;
        $this->assertInstanceOf('Exception', $e);
        $this->assertInstanceOf('LogicException', $e);
        $this->assertInstanceOf('axy\errors\Error', $e);
        $this->assertInstanceOf('axy\errors\Logic', $e);
        $this->assertInstanceOf('axy\errors\InvalidConfig', $e);
        $this->assertInstanceOf('\axy\errors\tests\tst\errors\Error', $e);
        $this->assertSame('Config has an invalid format: "errmsg"', $e->getMessage());
        $this->assertSame($context->file, $e->getFile());
        $this->assertSame($context->line, $e->getLine());
        $this->assertSame($context->obj->file, $e->getOriginalFile());
        $this->assertSame($context->obj->line, $e->getOriginalLine());
        $trace = $e->getTruncatedTrace();
        $this->assertInstanceOf('axy\backtrace\ExceptionTrace', $trace);
        $this->assertSame($context->file, $trace->file);
        $this->assertSame($context->line, $trace->line);
        $this->assertSame($context->obj->file, $trace->originalFile);
        $this->assertSame($context->obj->line, $trace->originalLine);
        $this->assertEquals($e->getTrace(), $trace->originalItems);
        $this->assertSame($context->obj->file, $trace->originalItems[0]['file']);
        $this->assertSame($context->file, $trace->items[0]['file']);
        $this->assertSame(2, count($e->getTrace()) - count($trace));
    }

    public function testAxyNS()
    {
        $context = $this->getErrorContext(false);
        $e = $context->e;
        $this->assertInstanceOf('axy\errors\InvalidConfig', $e);
        $this->assertNotInstanceOf('\axy\errors\tests\tst\errors\Error', $e);
        $this->assertSame('Config has an invalid format: "no msg"', $e->getMessage());
        $this->assertSame($context->obj->file, $e->getFile());
        $this->assertSame($context->obj->line, $e->getLine());
        $this->assertSame($context->obj->file, $e->getOriginalFile());
        $this->assertSame($context->obj->line, $e->getOriginalLine());
        $trace = $e->getTruncatedTrace();
        $this->assertInstanceOf('axy\backtrace\ExceptionTrace', $trace);
        $this->assertSame($context->obj->file, $trace->file);
        $this->assertSame($context->obj->line, $trace->line);
        $this->assertSame($context->obj->file, $trace->originalFile);
        $this->assertSame($context->obj->line, $trace->originalLine);
        $this->assertEquals($e->getTrace(), $trace->items);
        $this->assertEquals($e->getTrace(), $trace->originalItems);
        $this->assertSame($context->obj->file, $trace->items[0]['file']);
    }

    public function testOutNS()
    {
        $line = null;
        try {
            $line = __LINE__ + 1;
            throw new CustomInvalidConfig();
        } catch (CustomInvalidConfig $e) {
        }
        $this->assertSame(__FILE__, $e->getFile());
        $this->assertSame($line, $e->getLine());
        $this->assertSame(__FILE__, $e->getOriginalFile());
        $this->assertSame($line, $e->getOriginalLine());
        $trace = $e->getTruncatedTrace();
        $this->assertInstanceOf('axy\backtrace\ExceptionTrace', $trace);
        $this->assertSame(__FILE__, $trace->file);
        $this->assertSame($line, $trace->line);
        $this->assertSame(__FILE__, $trace->originalFile);
        $this->assertSame($line, $trace->originalLine);
        $this->assertEquals($e->getTrace(), $trace->items);
        $this->assertEquals($e->getTrace(), $trace->originalItems);
    }

    public function testNotTruncate()
    {
        $obj = new Invalid(true);
        $e = null;
        try {
            $obj->pointless();
            $this->fail('not thrown');
        } catch (Pointless $e) {
        }
        $this->assertSame($obj->file, $e->getFile());
        $this->assertSame($obj->line, $e->getLine());
    }

    public function testNativeTruncate()
    {
        if (version_compare(PHP_VERSION, '7.0.0', '>=')) {
            $this->markTestSkipped('Truncate native trace is not supported since PHP7');
        }
        $obj = new Invalid(true);
        $e = null;
        try {
            $obj->truncated();
            $this->fail('not thrown');
        } catch (Truncated $e) {
        }
        $originalTrace = $e->getTrace();
        $truncatedTrace = $e->getTruncatedTrace()->items;
        $this->assertNotEmpty($originalTrace);
        $this->assertEquals(count($originalTrace), count($truncatedTrace));
        $this->assertEquals($originalTrace[0], $truncatedTrace[0]);
        $this->assertEquals(__FILE__, $originalTrace[0]['file']);
    }

    public function testThrowerNull()
    {
        $line = null;
        $e = null;
        try {
            $line = __LINE__ + 1;
            Container::thrower(null);
            $this->fail('not thrown');
        } catch (CustomInvalidConfig $e) {
        }
        $this->assertSame(__FILE__, $e->getFile());
        $this->assertSame($line, $e->getLine());
    }

    public function testThrowerThis()
    {
        $e = null;
        try {
            Container::thrower(true);
            $this->fail('not thrown');
        } catch (CustomInvalidConfig $e) {
        }
        $this->assertSame(Container::$file, $e->getFile());
        $this->assertSame(Container::$line, $e->getLine());
    }

    public function testThrowerNS()
    {
        $line = null;
        $e = null;
        try {
            $line = __LINE__ + 1;
            Container::thrower('axy\errors\tests\tst');
            $this->fail('not thrown');
        } catch (CustomInvalidConfig $e) {
        }
        $this->assertSame(__FILE__, $e->getFile());
        $this->assertSame($line, $e->getLine());
    }
}
