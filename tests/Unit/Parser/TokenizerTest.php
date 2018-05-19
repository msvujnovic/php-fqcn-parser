<?php

namespace PhpFqcnParser\Tests\Unit\Parser;

use PhpFqcnParser\Parser\Token;
use PhpFqcnParser\Parser\Tokenizer;

/**
 * @author Marko S. Vujnovic <msvujnovic@gmail.com>
 * @since  19.05.2018
 */
class TokenizerTest extends \PHPUnit_Framework_TestCase
{
    public function testTokenizeReturnsProperNumberOfTokenObjects()
    {
        $input = 'namespace Foo;';
        $expectedTokens = token_get_all($input);

        $tokenizer = new Tokenizer();
        $tokens = $tokenizer->tokenize($input);
        self::containsOnlyInstancesOf(Token::class);
        self::assertCount(count($expectedTokens), $tokens);
    }
    
    public function testTokenizeReturnsProperToken()
    {
        $input = 'namespace';
        $expectedToken = token_get_all($input)[0];

        $tokenizer = new Tokenizer();
        $tokens = $tokenizer->tokenize($input);

        $reflection = new \ReflectionObject($tokens[0]);
        $tokenProperty = $reflection->getProperty('token');
        $tokenProperty->setAccessible(true);
        $tokenPropertyValue = $tokenProperty->getValue($tokens[0]);
        
        self::assertEquals($expectedToken, $tokenPropertyValue);
    }
}
