<?php

namespace Pagekit\Kernel;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface HttpKernelInterface
{
    /**
     * Gets the current request.
     */
    public function getRequest(): ?Request;

    /**
     * Checks if this is a master request.
     */
    public function isMasterRequest(): bool;

    /**
     * Handles the request.
     *
     * @param  Request $request
     */
    public function handle(Request $request): Response;

    /**
     * Aborts the current request with HTTP exception.
     *
     * @param  int    $code
     * @param  string $message
     * @param  array  $headers
     * @throws HttpException
     */
    public function abort($code, $message = null, array $headers = []);

    /**
     * Terminates the current request.
     *
     * @param Request  $request
     * @param Response $response
     */
    public function terminate(Request $request, Response $response);
}
