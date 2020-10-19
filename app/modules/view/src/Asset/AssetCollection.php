<?php

namespace Pagekit\View\Asset;

use Pagekit\View\Asset\AssetInterface;

class AssetCollection implements \IteratorAggregate, \Countable
{
    /**
     * @var AssetInterface[]
     */
    protected array $assets;

    /**
     * Constructor.
     *
     * @param array $assets
     */
    public function __construct(array $assets = [])
    {
        $this->assets = $assets;
    }

    /**
     * Gets asset from collection.
     *
     * @param  string $name
     */
    public function get($name): ?AssetInterface
    {
        return isset($this->assets[$name]) ? $this->assets[$name] : null;
    }

    /**
     * Adds asset to collection.
     *
     * @param AssetInterface $asset
     */
    public function add(AssetInterface $asset): void
    {
        $this->assets[$asset->getName()] = $asset;
    }

    /**
     * Replace asset in collection.
     *
     * @param string         $name
     * @param AssetInterface $asset
     */
    public function replace($name, AssetInterface $asset): void
    {
        $assets = [];

        foreach ($this->assets as $key => $val) {
            if ($key == $name) {
                $assets[$asset->getName()] = $asset;
            } else {
                $assets[$key] = $val;
            }
        }

        $this->assets = $assets;
    }

    /**
     * Removes assets from collection.
     *
     * @param string|array $name
     */
    public function remove($name): void
    {
        $names = (array) $name;

        foreach ($names as $name) {
            unset($this->assets[$name]);
        }
    }

    /**
     * Gets the unique hash of the collection.
     *
     * @param  string $salt
     */
    public function hash($salt = ''): string
    {
        $hashes = [];

        foreach ($this as $asset) {
            $hashes[] = $asset->hash($salt);
        }

        return hash('crc32b', implode('', $hashes));
    }

    /**
     * Dumps collection to a string.
     *
     * @param  array $filters
     */
    public function dump(array $filters = []): string
    {
        $data = '';

        foreach ($this as $asset) {
            $data .= $asset->dump($filters)."\n\n";
        }

        return $data;
    }

    /**
     * Gets all asset names.
     */
    public function names(): array
    {
        return array_keys($this->assets);
    }

    /**
     * Countable interface implementation.
     */
    public function count(): int
    {
        return count($this->assets);
    }

    /**
     * IteratorAggregate interface implementation.
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->assets);
    }
}
