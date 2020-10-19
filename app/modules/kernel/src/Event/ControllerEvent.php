<?php

namespace Pagekit\Kernel\Event;

class ControllerEvent extends KernelEvent
{
    use ResponseTrait;

    /**
     * @var callable
     */
    protected $controller;

    /**
     * @var mixed
     */
    protected $controllerResult;

    /**
     * Gets the controller.
     */
    public function getController(): callable
    {
        return $this->controller;
    }

    /**
     * Sets the controller.
     *
     * @param callable $controller
     */
    public function setController(callable $controller): void
    {
        $this->controller = $controller;
    }

    /**
     * Gets the controller result.
     *
     * @return mixed
     */
    public function getControllerResult()
    {
        return $this->controllerResult;
    }

    /**
     * Sets the controller result.
     *
     * @param mixed $controllerResult
     */
    public function setControllerResult($controllerResult): void
    {
        $this->controllerResult = $controllerResult;
    }
}
