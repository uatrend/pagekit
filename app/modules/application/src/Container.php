<?php

namespace Pagekit;

class Container implements \ArrayAccess
{
    protected array $values = [];

    protected array $raw = [];

    protected array $factories = [];

    /**
     * Constructor.
     *
     * @param array $values
     */
    public function __construct(array $values = [])
    {
        foreach ($values as $name => $value) {
            $this->offsetSet($name, $value);
        }

        if (in_array('Pagekit\Application\Traits\StaticTrait', class_uses($this))) {
            static::$instance = $this;
        }
    }

    /**
     * Gets a parameter/service or calls the invoke method.
     *
     * @param  string $name
     * @param  array  $args
     * @return mixed
     */
    public function __call($name, $args)
    {
        return $args ? call_user_func_array($this->offsetGet($name), $args) : $this->offsetGet($name);
    }

    /**
     * Sets a closure as a factory service.
     *
     * @param string   $name
     * @param \Closure $closure
     */
    public function factory($name, \Closure $closure): void
    {
        $this->offsetSet($name, $closure);
        $this->factories[$name] = true;
    }

    /**
     * Extends an existing service definition.
     *
     * @param string   $name
     * @param \Closure $closure
     *
     * @throws \InvalidArgumentException
     */
    public function extend($name, \Closure $closure): void
    {
        if (!array_key_exists($name, $this->values)) {
            throw new \InvalidArgumentException(sprintf('"%s" is not defined.', $name));
        }

        if (!($this->values[$name] instanceof \Closure)) {
            throw new \InvalidArgumentException(sprintf('"%s" service definition is not a Closure.', $name));
        }

        $factory = $this->values[$name];

        $this->offsetSet($name, fn($c) => $closure($factory($c), $c));
    }

    /**
     * Gets a parameter/service without resolving.
     *
     * @param  string $name
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    public function raw($name)
    {
        if (!array_key_exists($name, $this->values)) {
            throw new \InvalidArgumentException(sprintf('"%s" is not defined.', $name));
        }

        return isset($this->raw[$name]) ? $this->raw[$name] : $this->values[$name];
    }

    /**
     * Returns all defined names.
     */
    public function keys(): array
    {
        return array_keys($this->values);
    }

    /**
     * Checks if a parameter/service is defined.
     *
     * @param  string $name
     */
    public function offsetExists($name): bool
    {
        return array_key_exists($name, $this->values);
    }

    /**
     * Gets a parameter/service.
     *
     * @param  string $name
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    public function offsetGet($name)
    {
        if (!array_key_exists($name, $this->values)) {
            throw new \InvalidArgumentException(sprintf('"%s" is not defined.', $name));
        }

        if (array_key_exists($name, $this->raw) || !($this->values[$name] instanceof \Closure)) {
            return $this->values[$name];
        }

        if (isset($this->factories[$name])) {
            return $this->values[$name]($this);
        }

        $this->raw[$name] = $this->values[$name];

        return $this->values[$name] = $this->values[$name]($this);
    }

    /**
     * Sets a parameter/service.
     *
     * @param string $name
     * @param mixed  $value
     *
     * @throws \RuntimeException
     */
    public function offsetSet($name, $value): void
    {
        if (array_key_exists($name, $this->raw)) {
            throw new \RuntimeException(sprintf('Cannot override service definition "%s".', $name));
        }

        $this->values[$name] = $value;
    }

    /**
     * Removes a parameter/service.
     *
     * @param string $name
     */
    public function offsetUnset($name): void
    {
        if (array_key_exists($name, $this->values)) {
            unset($this->values[$name], $this->raw[$name], $this->factories[$name]);
        }
    }
}
