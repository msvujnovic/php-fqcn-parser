<?php

namespace PhpFqcnParser\Tests\Integration;

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
}
