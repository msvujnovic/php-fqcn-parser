<?php

namespace PhpFqcnParser\Parser;

use Illuminate\Support\Collection;

/**
 * @author Marko S. Vujnovic <msvujnovic@gmail.com>
 * @since  19.05.2018
 */
class Tokenizer
{
    /**
     * @param $string
     *
     * @return Collection|LinkedListToken[]
     */
    public function tokenize($string)
    {
        $tokens = Collection::make(token_get_all($string))->map(function ($token) {
            return new LinkedListToken(new Token($token));
        });

        $tokens->each(function (LinkedListToken $token, $offset) use ($tokens) {
            $nextOffset = $offset + 1;
            if ($tokens->has($nextOffset)) {
                $token->setNext($tokens[$nextOffset]);
            }
        });

        return $tokens;
    }
}
