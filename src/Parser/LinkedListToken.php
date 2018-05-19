<?php

namespace PhpFqcnParser\Parser;

/**
 * @author Marko S. Vujnovic <msvujnovic@gmail.com>
 * @since  19.05.2018
 */
class LinkedListToken
{
    /**
     * @var Token
     */
    protected $token;

    /**
     * @var LinkedListToken
     */
    protected $next;

    /**
     * LinkedListToken constructor.
     *
     * @param Token $token
     */
    public function __construct(Token $token)
    {
        $this->token = $token;
    }

    /**
     * @return null|LinkedListToken
     */
    public function next()
    {
        return $this->next;
    }

    /**
     * @return bool
     */
    public function hasNext()
    {
        return $this->next === null ? false : true;
    }

    /**
     * @param LinkedListToken $next
     *
     * @return LinkedListToken
     */
    public function setNext($next)
    {
        $this->next = $next;
        return $this;
    }

    /**
     * @return Token
     */
    public function getToken()
    {
        return $this->token;
    }
}
