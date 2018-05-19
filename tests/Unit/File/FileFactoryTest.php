<?php

namespace PhpFqcnParser\Tests\Unit\File;

use PhpFqcnParser\File\File;
use PhpFqcnParser\File\FileFactory;

/**
 * @author Marko S. Vujnovic <msvujnovic@gmail.com>
 * @since  19.05.2018
 */
class FileFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateFromRelativeAndBasePathReturnsTwoFileObjectsIfPassedTwoPaths()
    {
        $factory = new FileFactory();
        $results = $factory->createFromRelativeAndBasePath([uniqid(), uniqid()], uniqid());

        self::assertContainsOnlyInstancesOf(File::class, $results);
        self::assertCount(2, $results);
    }

    public function testCreateFromRelativeAndBasePathReturnsFileObjectWithCorrectAbsolutePath()
    {
        $relativePath = uniqid();
        $basePath = uniqid();

        $factory = new FileFactory();
        $results = $factory->createFromRelativeAndBasePath([$relativePath], $basePath);

        $reflection = new \ReflectionObject($results[0]);
        $absolutePathProperty = $reflection->getProperty('absolutePath');
        $absolutePathProperty->setAccessible(true);
        $absolutePath = $absolutePathProperty->getValue($results[0]);

        self::assertEquals($basePath . DIRECTORY_SEPARATOR . $relativePath, $absolutePath);
    }

    public function testCreateFromAbsolutePathsReturnsTwoFileObjectsIfPassedTwoPaths()
    {
        $factory = new FileFactory();
        $results = $factory->createFromAbsolutePaths([uniqid(), uniqid()]);

        self::assertContainsOnlyInstancesOf(File::class, $results);
        self::assertCount(2, $results);
    }

    public function testCreateFromAbsolutePathsReturnsFileObjectWithCorrectAbsolutePath()
    {
        $expectedAbsolutePath = uniqid();

        $factory = new FileFactory();
        $results = $factory->createFromAbsolutePaths([$expectedAbsolutePath]);

        $reflection = new \ReflectionObject($results[0]);
        $absolutePathProperty = $reflection->getProperty('absolutePath');
        $absolutePathProperty->setAccessible(true);
        $absolutePath = $absolutePathProperty->getValue($results[0]);

        self::assertEquals($expectedAbsolutePath, $absolutePath);
    }
}
