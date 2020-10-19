<?php

namespace Pagekit\Auth\Event;

use Symfony\Component\HttpFoundation\Response;

class GetResponseEvent extends Event
{
    protected ?\Symfony\Component\HttpFoundation\Response $response = null;

    /**
     * Returns the response object
     */
    public function getResponse(): ?\Symfony\Component\HttpFoundation\Response
    {
        return $this->response;
    }

    /**
     * Sets a response and stops event propagation
     *
     * @param Response $response
     */
    public function setResponse(Response $response): void
    {
        $this->response = $response;
        $this->stopPropagation();
    }

    /**
     * Returns whether a response was set
     *
     * @return Boolean Whether a response was set
     */
    public function hasResponse(): bool
    {
        return null !== $this->response;
    }
}
