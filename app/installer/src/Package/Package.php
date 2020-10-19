<?php

namespace Pagekit\Installer\Package;

use Pagekit\Util\Arr;

class Package implements PackageInterface
{
    protected array $data;

    /**
     * Constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * {@inheritdoc}
     */
    public function get($key, $default = null)
    {
        return Arr::get($this->data, $key, $default);
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value): void
    {
        Arr::set($this->data, $key, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return $this->get('name');
    }

    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return $this->get('type');
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize(): array
    {
        return $this->data;
    }
}
