<?php

namespace Pagekit\Kernel\Event;

use Pagekit\Event\Event;
use Pagekit\Kernel\HttpKernelInterface;

class KernelEvent extends Event
{
    protected \Pagekit\Kernel\HttpKernelInterface $kernel;

    /**
     * Constructor.
     *
     * @param string              $name
     * @param HttpKernelInterface $kernel
     */
    public function __construct($name, HttpKernelInterface $kernel)
    {
        parent::__construct($name);

        $this->kernel = $kernel;
    }

    /**
     * Gets the kernel.
     */
    public function getKernel(): HttpKernelInterface
    {
        return $this->kernel;
    }

    /**
     * Checks if this is a master request.
     */
    public function isMasterRequest(): bool
    {
        return $this->kernel->isMasterRequest();
    }
}
