<?php

namespace PhpFqcnParser\Parser;

use Illuminate\Support\Collection;

/**
 * @author Marko S. Vujnovic <msvujnovic@gmail.com>
 * @since  19.05.2018
 */
class Parser
{
    /**
     * @param $string
     *
     * @return Collection|string[]
     */
    public function getFqcns($string)
    {
        $namespace = "";
        $classes = Collection::make([]);

        $tokenizer = new Tokenizer();
        foreach ($tokenizer->tokenize($string) as $token) {
            if ($this->isTokenValidNamespaceDeclaration($token)) {
                $namespace = $this->readNamespace($token);
            } elseif ($this->isTokenValidClassDeclaration($token)) {
                $classes->push($this->readClass($token));
            }
        }

        return $this->mapClassesToNamespace($classes, $namespace);
    }

    /**
     * @param LinkedListToken $linkedListToken
     *
     * @return bool
     */
    protected function isTokenValidNamespaceDeclaration(LinkedListToken $linkedListToken)
    {
        return $this->isTokenValidClassOrNamespaceDeclaration($linkedListToken) &&
            $linkedListToken->getToken()->isNamespaceKeyword();
    }

    /**
     * @param LinkedListToken $linkedListToken
     *
     * @return bool
     */
    protected function isTokenValidClassOrNamespaceDeclaration(LinkedListToken $linkedListToken)
    {
        // if the current token doesn't have two tokens after it, it can't a be valid declaration
        return $linkedListToken->hasNext() && $linkedListToken->next()->hasNext();
    }

    /**
     * @param LinkedListToken $linkedListToken
     *
     * @return string
     */
    protected function readNamespace(LinkedListToken $linkedListToken)
    {
        /*
         * we are reading the namespace from the second next token of the current token because the first
         * next token is a whitespace
         */
        return $this->readNamespaceRecursively($linkedListToken->next()->next());
    }

    /**
     * @param LinkedListToken $linkedListToken
     *
     * @return string
     */
    protected function readNamespaceRecursively(LinkedListToken $linkedListToken)
    {
        $token = $linkedListToken->getToken();

        if (($token->isString() || $token->isNamespacesSeparator()) && $linkedListToken->hasNext()) {
            return $token->getValue() . $this->readNamespaceRecursively($linkedListToken->next());
        }

        return '';
    }

    /**
     * @param LinkedListToken $linkedListToken
     *
     * @return bool
     */
    protected function isTokenValidClassDeclaration(LinkedListToken $linkedListToken)
    {
        return $this->isTokenValidClassOrNamespaceDeclaration($linkedListToken) &&
            $linkedListToken->getToken()->isClassKeyword();
    }

    /**
     * @param LinkedListToken $linkedListToken
     *
     * @return string
     */
    protected function readClass(LinkedListToken $linkedListToken)
    {
        /*
         * we are reading the namespace or class from the second next token of the current token because the first
         * next token is a whitespace
         */
        return $linkedListToken->next()->next()->getToken()->getValue();
    }

    /**
     * @param Collection $classes
     * @param string     $namespace
     *
     * @return Collection|string[]
     */
    protected function mapClassesToNamespace(Collection $classes, $namespace)
    {
        return $classes->map(function ($class) use ($namespace) {
            return $namespace ? $namespace . '\\' . $class : $class;
        });
    }
}
