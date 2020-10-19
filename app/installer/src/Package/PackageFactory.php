<?php

namespace Pagekit\Installer\Package;

use Pagekit\Application as App;

class PackageFactory implements \ArrayAccess, \IteratorAggregate
{
    protected array $paths = [];

    protected array $packages = [];

    /**
     * Get shortcut.
     *
     * @see get()
     */
    public function __invoke($name)
    {
        return $this->get($name);
    }

    /**
     * Gets a package.
     *
     * @param  string $name
     * @param  bool   $force
     * @return mixed|null
     */
    public function get($name, $force = false)
    {
        if ($force || empty($this->packages)) {
            $this->loadPackages();
        }

        return isset($this->packages[$name]) ? $this->packages[$name] : null;
    }

    /**
     * Gets all packages.
     *
     * @param  string $type
     * @param bool    $force
     */
    public function all($type = null, $force = false): array
    {
        if ($force || empty($this->packages)) {
            $this->loadPackages();
        }

        $filter = fn($package) => $package->get('type') == $type;

        return $type !== null ? array_filter($this->packages, $filter) : $this->packages;
    }

    /**
     * Loads a package from data.
     *
     * @param  string|array $data
     * @return Package
     */
    public function load($data)
    {
        if (is_string($data) && strpos($data, '{') !== 0) {
            $path = strtr(dirname($data), '\\', '/');
            $data = @file_get_contents($data);
        }

        if (is_string($data)) {
            $data = @json_decode($data, true);
        }

        if (is_array($data) && isset($data['name'])) {

            if (!isset($data['module'])) {
                $data['module'] = basename($data['name']);
            }

            if (isset($path)) {
                $data['path'] = $path;
                $data['url'] = App::url()->getStatic($path);
            }

            return new Package($data);
        }
    }

    /**
     * Adds a package path(s).
     *
     * @param  string|array $paths
     */
    public function addPath($paths): self
    {
        $this->paths = array_merge($this->paths, (array) $paths);

        return $this;
    }

    /**
     * Checks if a package exists.
     *
     * @param  string $name
     */
    public function offsetExists($name): bool
    {
        return isset($this->packages[$name]);
    }

    /**
     * Gets a package by name.
     *
     * @param  string $name
     */
    public function offsetGet($name): bool
    {
        return $this->get($name);
    }

    /**
     * Sets a package.
     *
     * @param string $name
     * @param string $package
     */
    public function offsetSet($name, $package): void
    {
        $this->packages[$name] = $package;
    }

    /**
     * Unset a package.
     *
     * @param string $name
     */
    public function offsetUnset($name): void
    {
        unset($this->packages[$name]);
    }

    /**
     * Implements the IteratorAggregate.
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->packages);
    }

    /**
     * Load packages from paths.
     */
    protected function loadPackages(): void
    {
        foreach ($this->paths as $path) {

            $paths = glob($path, GLOB_NOSORT) ?: [];

            foreach ($paths as $p) {

                if (!$package = $this->load($p)) {
                    continue;
                }

                $this->packages[$package->getName()] = $package;
            }
        }
    }
}
