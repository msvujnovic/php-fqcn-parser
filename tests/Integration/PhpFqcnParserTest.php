<?php

namespace PhpFqcnParser\Tests\Integration;

use PhpFqcnParser\InvalidOptionException;
use PhpFqcnParser\Options;
use PhpFqcnParser\PhpFqcnParser;

/**
 * @author Marko S. Vujnovic <msvujnovic@gmail.com>
 * @since  18.05.2018
 */
class PhpFqcnParserTest extends \PHPUnit_Framework_TestCase
{
    public function testTwoRelativePathsAreSubmittedOneFileWithNamespaceAnotherWithout()
    {
        $parser = new PhpFqcnParser();
        $options = new Options(Options::PATH_TYPE_RELATIVE, dirname(__FILE__));
        $fqcns = $parser->execute(['sample-no-ns.php', 'sample-with-ns.php'], $options);

        self::assertContains('FooBarBazQuuxNoNamespace', $fqcns);
        self::assertContains('Completely\Imaginary\Namespac\FooBarBazQuux', $fqcns);
    }

    public function testTwoAbsolutePathsAreSubmittedOneFileWithNamespaceAnotherWithout()
    {
        $parser = new PhpFqcnParser();
        $options = new Options(Options::PATH_TYPE_ABSOLUTE);

        $fqcns = $parser->execute(
            [realpath(__DIR__ . '/sample-no-ns.php'), realpath(__DIR__ . '/sample-with-ns.php')],
            $options
        );

        self::assertContains('FooBarBazQuuxNoNamespace', $fqcns);
        self::assertContains('Completely\Imaginary\Namespac\FooBarBazQuux', $fqcns);
    }

    public function testFilesWithMultipleClassesAreParsedCorrectly()
    {
        $parser = new PhpFqcnParser();
        $options = new Options(Options::PATH_TYPE_RELATIVE, dirname(__FILE__));
        $fqcns = $parser->execute(['sample-with-ns-two-classes.php'], $options);

        self::assertContains('Completely\Imaginary\Namespac\FooBar', $fqcns);
        self::assertContains('Completely\Imaginary\Namespac\BazQuux', $fqcns);
    }

    public function testInvalidOptionExceptionIsThrownIfPathTypeIsRelativeAndBasePathIsEmpty()
    {
        $parser = new PhpFqcnParser();
        $options = new Options(Options::PATH_TYPE_RELATIVE);
        self::expectException(InvalidOptionException::class);
        $parser->execute(['foo'], $options);
    }
}
