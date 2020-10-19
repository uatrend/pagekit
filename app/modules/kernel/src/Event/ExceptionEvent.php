<?php

namespace Pagekit\Kernel\Event;

use Pagekit\Kernel\HttpKernelInterface;

class ExceptionEvent extends KernelEvent
{
    use ResponseTrait;

    protected ?\Exception $exception = null;

    /**
     * Construct.
     *
     * @param string              $name
     * @param HttpKernelInterface $kernel
     * @param \Exception $e
     */
    public function __construct($name, HttpKernelInterface $kernel, \Exception $e)
    {
        parent::__construct($name, $kernel);

        $this->setException($e);
    }

    /**
     * Gets the thrown exception.
     */
    public function getException(): ?\Exception
    {
        return $this->exception;
    }

    /**
     * Sets the thrown exception.
     *
     * @param \Exception $exception
     */
    public function setException(\Exception $exception): void
    {
        $this->exception = $exception;
    }
}
