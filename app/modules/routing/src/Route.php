<?php

namespace Pagekit\Routing;

use Symfony\Component\Routing\Route as BaseRoute;

class Route extends BaseRoute
{
    protected string $name = '';

    /**
     * Returns the routes name.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Sets the routes name
     *
     * @param  string $name
     */
    public function setName($name): self
    {
        $this->name = trim((string) $name, '/');

        return $this;
    }

    /**
     * Gets the controller.
     *
     * @return mixed
     */
    public function getController()
    {
        $controller = $this->getDefault('_controller');

        if (is_string($controller)) {
            return explode('::', $controller, 2);
        }

        return $controller;
    }

    /**
     * Gets the controller reflection class.
     *
     * @return \ReflectionClass
     */
    public function getControllerClass()
    {
        $controller = $this->getController();

        if (is_array($controller)) {
            return new \ReflectionClass($controller[0]);
        }
    }

    /**
     * Gets the controller reflection method.
     *
     * @return \ReflectionMethod
     */
    public function getControllerMethod()
    {
        $controller = $this->getController();

        if (is_array($controller)) {
            return new \ReflectionMethod($controller[0], $controller[1]);
        }
    }
}
