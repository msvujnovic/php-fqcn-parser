<?php

namespace PhpFqcnParser\Tests\Unit\Parser;

use PhpFqcnParser\Parser\LinkedListToken;
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
        $input = '<?php namespace Foo;';
        $expectedTokens = token_get_all($input);

        $tokenizer = new Tokenizer();
        $tokens = $tokenizer->tokenize($input);
        self::containsOnlyInstancesOf(LinkedListToken::class);
        self::assertCount(count($expectedTokens), $tokens);
    }
    
    public function testTokenizeReturnsProperToken()
    {
        $input = '<?php';
        $expectedToken = token_get_all($input)[0];

        $tokenizer = new Tokenizer();
        $tokens = $tokenizer->tokenize($input);

        $token = $tokens->shift()->getToken();
        $tokenPropertyValue = $this->getTokenPropertyValue($token);
        
        self::assertEquals($expectedToken, $tokenPropertyValue);
    }

    /**
     * @param Token $token
     *
     * @return mixed
     */
    private function getTokenPropertyValue(Token $token)
    {
        $reflection = new \ReflectionObject($token);
        $tokenProperty = $reflection->getProperty('token');
        $tokenProperty->setAccessible(true);
        $tokenPropertyValue = $tokenProperty->getValue($token);
        return $tokenPropertyValue;
    }

    public function testTokenizeLinksTokensProperly()
    {
        $input = '<?php namespace';
        $expectedTokens = token_get_all($input);

        $tokenizer = new Tokenizer();
        $tokens = $tokenizer->tokenize($input);

        /** @var LinkedListToken $firstToken */
        $firstToken = $tokens->shift();
        self::assertTrue($firstToken->hasNext());

        $firstConcreteToken = $firstToken->getToken();
        $firstConcreteTokenPropertyValue = $this->getTokenPropertyValue($firstConcreteToken);
        self::assertEquals($expectedTokens[0], $firstConcreteTokenPropertyValue);

        $secondToken = $firstToken->next();
        self::assertFalse($secondToken->hasNext());

        $secondConcreteToken = $secondToken->getToken();
        $secondConcreteTokenPropertyValue = $this->getTokenPropertyValue($secondConcreteToken);
        self::assertEquals($expectedTokens[1], $secondConcreteTokenPropertyValue);
    }
}
