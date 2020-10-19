<?php

namespace Pagekit\Config;

use Pagekit\Util\Arr;

class Config implements \ArrayAccess, \Countable, \JsonSerializable
{
    protected array $values = [];

    protected bool $dirty = false;

    /**
     * Constructor.
     *
     * @param mixed $values
     */
    public function __construct($values = [])
    {
        $this->values = (array) $values;
    }

    /**
     * Checks if the given key exists.
     *
     * @param  string $key
     */
    public function has($key): bool
    {
        return Arr::has($this->values, $key);
    }

    /**
     * Gets a value by key.
     *
     * @param  string $key
     * @param  mixed  $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return Arr::get($this->values, $key, $default);
    }

    /**
     * Sets a value.
     *
     * @param  string $key
     * @param  mixed  $value
     */
    public function set($key, $value): self
    {
        Arr::set($this->values, $key, $value);

        $this->dirty = true;

        return $this;
    }

    /**
     * Removes one or more values.
     *
     * @param  array|string $keys
     */
    public function remove($keys): self
    {
        Arr::remove($this->values, $keys);

        $this->dirty = true;

        return $this;
    }

    /**
     * Push value to the end of array.
     *
     * @param  string $key
     * @param  mixed  $value
     */
    public function push($key, $value): self
    {
        $values = $this->get($key);

        $values[] = $value;

        return $this->set($key, $values);
    }

    /**
     * Removes a value from array.
     *
     * @param  string $key
     * @param  mixed  $value
     */
    public function pull($key, $value): self
    {
        $values = $this->get($key);

        Arr::pull($values, $value);

        return $this->set($key, $values);
    }

    /**
     * Merges values from another array.
     *
     * @param  mixed $values
     * @param  bool  $replace
     */
    public function merge($values, $replace = false): self
    {
        $this->values = Arr::merge($this->values, $values, $replace);
        $this->dirty  = true;

        return $this;
    }

    /**
     * Extracts config values.
     *
     * @param  array $keys
     * @param  bool  $include
     */
    public function extract($keys, $include = true): array
    {
        return Arr::extract($this->values, $keys, $include);
    }

    /**
     * Checks if the values are modified.
     */
    public function dirty(): bool
    {
        return $this->dirty;
    }

    /**
     * Dumps the values as php.
     */
    public function dump(): string
    {
        return '<?php return '.var_export($this->values, true).';';
    }

    /**
     * Gets the value count.
     */
    public function count(): int
    {
        return count($this->values);
    }

    /**
     * Gets the values as a plain array.
     */
    public function toArray(): array
    {
        return $this->values;
    }

    /**
     * Implements JsonSerializable interface.
     */
    public function jsonSerialize(): array
    {
        return $this->values;
    }

    /**
     * Implements ArrayAccess interface.
     *
     * @see has()
     */
    public function offsetExists($key): bool
    {
        return $this->has($key);
    }

    /**
     * Implements ArrayAccess interface.
     *
     * @see get()
     */
    public function offsetGet($key)
    {
        return $this->get($key);
    }

    /**
     * Implements ArrayAccess interface.
     *
     * @see set()
     */
    public function offsetSet($key, $value): void
    {
        $this->set($key, $value);
    }

    /**
     * Implements ArrayAccess interface.
     *
     * @see remove()
     */
    public function offsetUnset($key): void
    {
        $this->remove($key);
    }
}
