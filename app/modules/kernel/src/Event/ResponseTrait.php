<?php

namespace Pagekit\Kernel\Event;

use Symfony\Component\HttpFoundation\Response;

trait ResponseTrait
{
    protected ?Response $response = null;

    /**
     * Checks if a response was set.
     */
    public function hasResponse(): bool
    {
        return $this->response !== null;
    }

    /**
     * Gets the response.
     */
    public function getResponse(): Response
    {
        return $this->response;
    }

    /**
     * Sets the response.
     *
     * @param Response $response
     */
    public function setResponse(Response $response): void
    {
        $this->response = $response;
        $this->stopPropagation();
    }
}
