<?php

namespace PhpFqcnParser\Tests\Unit\File;

use PhpFqcnParser\File\File;
use PhpFqcnParser\File\FileNotFoundException;

/**
 * @author Marko S. Vujnovic <msvujnovic@gmail.com>
 * @since  19.05.2018
 */
class FileTest extends \PHPUnit_Framework_TestCase
{
    public function testDoesExistReturnsTrueIfFileExists()
    {
        $path = '/tmp/' . uniqid() . '.test';
        touch($path);

        $file = new File($path);
        self::assertTrue($file->doesExist());

        unlink($path);
    }

    public function testDoesExistReturnsFalseIfFileDoesNotExist()
    {
        $path = uniqid();
        $file = new File($path);
        self::assertFalse($file->doesExist());
    }

    public function testGetContentsReturnsContentsIfFileExists()
    {
        $path = '/tmp/' . uniqid() . '.test';
        $contents = uniqid();
        file_put_contents($path, $contents);

        $file = new File($path);
        self::assertEquals($contents, $file->getContents());

        unlink($path);
    }

    public function testGetContentsThrowsFileNotFoundExceptionIfFileDoesNotExist()
    {
        $path = uniqid();
        $file = new File($path);
        self::expectException(FileNotFoundException::class);
        $file->getContents();
    }
}
