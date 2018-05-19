<?php

namespace PhpFqcnParser\Parser;

/**
 * @author Marko S. Vujnovic <msvujnovic@gmail.com>
 * @since  19.05.2018
 */
class Parser
{
    /**
     * @param $string
     *
     * @return string
     */
    public function getFqCn($string)
    {
        $namespace = $class = "";
        $readNamespace = $readClass = false;

        $tokenizer = new Tokenizer();
        foreach ($tokenizer->tokenize($string) as $token) {
            if ($token->isNamespaceKeyword()) {
                $readNamespace = true;
                continue;
            }

            if ($token->isClassKeyword()) {
                $readClass = true;
                continue;
            }

            if ($readNamespace === true) {
                if ($token->isString() || $token->isNamespacesSeparator()) {
                    $namespace .= $token->getValue();
                } elseif ($token->isSemicolon()) {
                    $readNamespace = false;
                }
                continue;
            }

            if ($readClass === true && $token->isString()) {
                $class = $token->getValue();
                break;
            }
        }

        return $namespace ? $namespace . '\\' . $class : $class;
    }
}
