<?php

namespace PhpFqcnParser\Parser;

/**
 * @author Marko S. Vujnovic <msvujnovic@gmail.com>
 * @since  19.05.2018
 */
class Token
{
    /**
     * @var mixed
     */
    protected $token;

    /**
     * Token constructor.
     *
     * @param mixed $token
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * @return bool
     */
    public function isNamespaceKeyword()
    {
        return is_array($this->token) && $this->token[0] == T_NAMESPACE;
    }

    /**
     * @return bool
     */
    public function isClassKeyword()
    {
        return is_array($this->token) && $this->token[0] == T_CLASS;
    }

    /**
     * @return bool
     */
    public function isString()
    {
        return is_array($this->token) && $this->token[0] == T_STRING;
    }

    /**
     * @return bool
     */
    public function isNamespacesSeparator()
    {
        return is_array($this->token) && $this->token[0] == T_NS_SEPARATOR;
    }

    /**
     * @return bool
     */
    public function isSemicolon()
    {
        return $this->token === ';';
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return is_array($this->token) ? $this->token[1] : $this->token;
    }
}
