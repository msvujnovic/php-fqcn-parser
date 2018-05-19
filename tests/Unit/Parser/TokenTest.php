<?php

namespace PhpFqcnParser\Tests\Unit\Parser;

use PhpFqcnParser\Parser\Token;

/**
 * @author Marko S. Vujnovic <msvujnovic@gmail.com>
 * @since  19.05.2018
 */
class TokenTest extends \PHPUnit_Framework_TestCase
{
    public function testIsNamespaceKeywordReturnsTrue()
    {
        $input = '<?php namespace';

        $tokenMatched = false;
        foreach (token_get_all(($input)) as $token) {
            if ((new Token($token))->isNamespaceKeyword()) {
                $tokenMatched = true;
            }
        }

        self::assertTrue($tokenMatched);
    }

    public function testIsNamespaceKeywordReturnsFalse()
    {
        while ($input = uniqid() == 'namespace') {
        }
        $input = '<?php ' . $input;

        $tokenMatched = false;
        foreach (token_get_all(($input)) as $token) {
            if ((new Token($token))->isNamespaceKeyword()) {
                $tokenMatched = true;
            }
        }

        self::assertFalse($tokenMatched);
    }

    public function testIsClassKeywordReturnsTrue()
    {
        $input = '<?php class';

        $tokenMatched = false;
        foreach (token_get_all(($input)) as $token) {
            if ((new Token($token))->isClassKeyword()) {
                $tokenMatched = true;
            }
        }

        self::assertTrue($tokenMatched);
    }

    public function testIsClassKeywordReturnsFalse()
    {
        while ($input = uniqid() == 'class') {
        }
        $input = '<?php ' . $input;

        $tokenMatched = false;
        foreach (token_get_all(($input)) as $token) {
            if ((new Token($token))->isClassKeyword()) {
                $tokenMatched = true;
            }
        }

        self::assertFalse($tokenMatched);
    }

    public function testIsStringReturnsTrue()
    {
        $input = '<?php ' . uniqid();

        $tokenMatched = false;
        foreach (token_get_all(($input)) as $token) {
            if ((new Token($token))->isString()) {
                $tokenMatched = true;
            }
        }

        self::assertTrue($tokenMatched);
    }

    public function nonStringProvider()
    {
        return [[1], ['+'], [';'], ['\\'], ['(']];
    }

    /**
     * @dataProvider nonStringProvider
     *
     * @param $input
     */
    public function testIsStringReturnsFalse($input)
    {
        $input = '<?php ' . $input;

        $tokenMatched = false;
        foreach (token_get_all(($input)) as $token) {
            if ((new Token($token))->isString()) {
                $tokenMatched = true;
            }
        }

        self::assertFalse($tokenMatched);
    }

    public function testIsNamespaceSeparatorReturnsTrue()
    {
        $input = '<?php \\';

        $tokenMatched = false;
        foreach (token_get_all(($input)) as $token) {
            if ((new Token($token))->isNamespacesSeparator()) {
                $tokenMatched = true;
            }
        }

        self::assertTrue($tokenMatched);
    }

    public function nonNamespaceSeparatorProvider()
    {
        return [[1], ['+'], [';'], [uniqid()], ['(']];
    }

    /**
     * @dataProvider nonNamespaceSeparatorProvider
     *
     * @param $input
     */
    public function testIsNamespaceSeparatorReturnsFalse($input)
    {
        $input = '<?php ' . $input;

        $tokenMatched = false;
        foreach (token_get_all(($input)) as $token) {
            if ((new Token($token))->isNamespacesSeparator()) {
                $tokenMatched = true;
            }
        }

        self::assertFalse($tokenMatched);
    }

    public function testIsSemicolonReturnsTrue()
    {
        $input = '<?php ;';

        $tokenMatched = false;
        foreach (token_get_all(($input)) as $token) {
            if ((new Token($token))->isSemicolon()) {
                $tokenMatched = true;
            }
        }

        self::assertTrue($tokenMatched);
    }

    public function nonSemicolonProvider()
    {
        return [[1], ['+'], ['\\'], [uniqid()], ['(']];
    }

    /**
     * @dataProvider nonSemicolonProvider
     *
     * @param $input
     */
    public function testIsSemicolonReturnsFalse($input)
    {
        $input = '<?php ' . $input;

        $tokenMatched = false;
        foreach (token_get_all(($input)) as $token) {
            if ((new Token($token))->isSemicolon()) {
                $tokenMatched = true;
            }
        }

        self::assertFalse($tokenMatched);
    }

    public function testGetValueReturnsStringContentForParserToken()
    {
        $input = 'namespace';
        $token = new Token(token_get_all($input)[0]);
        self::assertEquals($input, $token->getValue());
    }

    public function testGetValueReturnsTokenForNonParserToken()
    {
        $input = ';';
        $token = new Token(token_get_all($input)[0]);
        self::assertEquals($input, $token->getValue());
    }
}
