<?php

namespace PhpFqcnParser\Tests\Unit\Parser;

use PhpFqcnParser\Parser\LinkedListToken;
use PhpFqcnParser\Parser\Token;

/**
 * @author Marko S. Vujnovic <msvujnovic@gmail.com>
 * @since  20.05.2018
 */
class LinkedListTokenTest extends \PHPUnit_Framework_TestCase
{
    public function testNextReturnsNext()
    {
        $first = new LinkedListToken(new Token(uniqid()));
        $second = new LinkedListToken(new Token(uniqid()));
        $first->setNext($second);

        self::assertSame($second, $first->next());
    }

    public function testNextReturnsNull()
    {
        $first = new LinkedListToken(new Token(uniqid()));

        self::assertNull($first->next());
    }

    public function testHasNextReturnsTrue()
    {
        $first = new LinkedListToken(new Token(uniqid()));
        $second = new LinkedListToken(new Token(uniqid()));
        $first->setNext($second);

        self::assertTrue($first->hasNext());
    }

    public function testHasNextReturnsFalse()
    {
        $first = new LinkedListToken(new Token(uniqid()));

        self::assertFalse($first->hasNext());
    }
}
