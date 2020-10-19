<?php

namespace Pagekit\Session\Csrf\Provider;

class DefaultCsrfProvider implements CsrfProviderInterface
{
    protected string $name;

    protected ?string $token = null;

    /**
     * Constructor.
     *
     * @param string $name
     */
    public function __construct($name = '_csrf')
    {
        $this->name = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function generate(): string
    {
        return sha1($this->getSessionId().$this->getSessionToken());
    }

    /**
     * {@inheritdoc}
     */
    public function validate($token = null): bool
    {
        if ($token === null) {
            $token = $this->token;
        }

        return $token === $this->generate();
    }

    /**
     * {@inheritdoc}
     */
    public function setToken($token): void
    {
        $this->token = $token;
    }

    /**
     * Returns the session id.
     */
    protected function getSessionId(): string
    {
        if (!session_id()) {
            session_start();
        }

        return session_id();
    }

    /**
     * Returns the session token.
     */
    protected function getSessionToken(): string
    {
        if (!isset($_SESSION[$this->name])) {
            $_SESSION[$this->name] = sha1(uniqid(rand(), true));
        }

        return $_SESSION[$this->name];
    }
}
