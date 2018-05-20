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
        $options = (new Options())->setPathTypeRelative(dirname(__FILE__));
        $fqcns = $parser->getFqcnsFromFiles(['sample-no-ns.php', 'sample-with-ns.php'], $options);

        self::assertContains('FooBarBazQuuxNoNamespace', $fqcns);
        self::assertContains('Completely\Imaginary\Namespac\FooBarBazQuux', $fqcns);
    }

    public function testTwoAbsolutePathsAreSubmittedOneFileWithNamespaceAnotherWithout()
    {
        $parser = new PhpFqcnParser();
        $options = (new Options())->setPathTypeAbsolute();

        $fqcns = $parser->getFqcnsFromFiles(
            [realpath(__DIR__ . '/sample-no-ns.php'), realpath(__DIR__ . '/sample-with-ns.php')],
            $options
        );

        self::assertContains('FooBarBazQuuxNoNamespace', $fqcns);
        self::assertContains('Completely\Imaginary\Namespac\FooBarBazQuux', $fqcns);
    }

    public function testFilesWithMultipleClassesAreParsedCorrectly()
    {
        $parser = new PhpFqcnParser();
        $options = (new Options())->setPathTypeRelative(dirname(__FILE__));
        $fqcns = $parser->getFqcnsFromFiles(['sample-with-ns-two-classes.php'], $options);

        self::assertContains('Completely\Imaginary\Namespac\FooBar', $fqcns);
        self::assertContains('Completely\Imaginary\Namespac\BazQuux', $fqcns);
    }
}
