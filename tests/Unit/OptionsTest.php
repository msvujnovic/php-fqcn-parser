<?php

namespace PhpFqcnParser\Tests\Unit;

use PhpFqcnParser\InvalidOptionException;
use PhpFqcnParser\Options;

/**
 * @author Marko S. Vujnovic <msvujnovic@gmail.com>
 * @since  19.05.2018
 */
class OptionsTest extends \PHPUnit_Framework_TestCase
{
    public function testSetPathTypeRelativeThrowsInvalidOptionExceptionOnInvalidBasePath()
    {
        self::expectException(InvalidOptionException::class);
        (new Options())->setPathTypeRelative(uniqid());
    }

    public function testIsPathRelativeReturnsTrueOnRelativePath()
    {
        self::assertTrue((new Options())->setPathTypeRelative(__DIR__)->isPathTypeRelative());
    }

    public function testIsPathRelativeReturnsFalseOnAbsolutePath()
    {
        self::assertFalse((new Options())->setPathTypeAbsolute()->isPathTypeRelative());
    }
}
