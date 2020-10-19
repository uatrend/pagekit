<?php

namespace Pagekit\Auth\Exception;

class BadCredentialsException extends AuthException
{
    /**
     * @var string[]
     */
    protected array $credentials;

    /**
     * Constructor.
     *
     * @param string[] $credentials
     */
    public function __construct(array $credentials)
    {
        $this->credentials = $credentials;
    }

    /**
     * Gets the login credentials.
     *
     * @return string[]
     */
    public function getCredentials(): array
    {
        return $this->credentials;
    }
}
