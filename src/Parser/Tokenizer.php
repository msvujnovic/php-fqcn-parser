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
     * @return Collection|Token[]
     */
    public function tokenize($string)
    {
        return Collection::make(token_get_all($string))
            ->map(function ($token) {
                return new Token($token);
            });
    }
}
