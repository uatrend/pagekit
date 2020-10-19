<?php

namespace Pagekit\Session\Csrf\Provider;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class SessionCsrfProvider extends DefaultCsrfProvider
{
    /**
     * The session.
     */
    protected \Symfony\Component\HttpFoundation\Session\Session $session;

    /**
     * Constructor.
     *
     * @param Session $session
     * @param string  $name
     */
    public function __construct(SessionInterface $session, $name = '_csrf')
    {
        parent::__construct($name);

        $this->session = $session;
    }

    /**
     * {@inheritdoc}
     */
    protected function getSessionId(): string
    {
        if (!$this->session->isStarted()) {
            $this->session->start();
        }

        return $this->session->getId();
    }

    /**
     * {@inheritdoc}
     */
    protected function getSessionToken(): string
    {
        if (!$this->session->has($this->name)) {
            $this->session->set($this->name, sha1(uniqid(rand(), true)));
        }

        return $this->session->get($this->name);
    }
}
