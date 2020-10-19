<?php

namespace Pagekit\Event;

use Pagekit\Util\Arr;
use \Pagekit\Event\EventDispatcherInterface;

class Event implements EventInterface, \ArrayAccess
{
    protected string $name;

    protected array $parameters;

    protected bool $propagationStopped = false;

    protected ?\Pagekit\Event\EventDispatcherInterface $dispatcher = null;

    /**
     * Constructor.
     *
     * @param string $name
     * @param array  $parameters
     */
    public function __construct($name, array $parameters = [])
    {
        $this->name = $name;
        $this->parameters = $parameters;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Sets the event name.
     *
     * @param  string $name
     */
    public function setName($name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Gets all parameters.
     *
     * @return array|object|\ArrayAccess
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * Sets all parameters.
     *
     * @param  array
     */
    public function setParameters(array $parameters): self
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * @param  mixed $values
     * @param  bool  $replace
     */
    public function addParameters(array $values, $replace = false): self
    {
        $this->parameters = Arr::merge($this->parameters, $values, $replace);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getDispatcher(): EventDispatcherInterface
    {
        return $this->dispatcher;
    }

    /**
     * Sets the event dispatcher.
     *
     * @param EventDispatcherInterface $dispatcher
     */
    public function setDispatcher(EventDispatcherInterface $dispatcher): void
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function isPropagationStopped(): bool
    {
        return $this->propagationStopped;
    }

    /**
     * {@inheritdoc}
     */
    public function stopPropagation(): void
    {
        $this->propagationStopped = true;
    }

    /**
     * Checks if a parameter exists.
     *
     * @param  string $name
     * @return mixed
     */
    public function offsetExists($name): bool
    {
        return isset($this->parameters[$name]);
    }

    /**
     * Gets a parameter.
     *
     * @param  string $name
     * @return mixed
     */
    public function offsetGet($name)
    {
        return isset($this->parameters[$name]) ? $this->parameters[$name] : null;
    }

    /**
     * Sets a parameter.
     *
     * @param  string   $name
     * @param  callable $callback
     */
    public function offsetSet($name, $callback): void
    {
        $this->parameters[$name] = $callback;
    }

    /**
     * Unsets a parameter.
     *
     * @param string $name
     */
    public function offsetUnset($name): void
    {
        unset($this->parameters[$name]);
    }
}
