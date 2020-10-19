<?php

namespace Pagekit\Installer\Package;

interface PackageInterface extends \JsonSerializable
{
    /**
     * Gets a package value.
     *
     * @param  string $key
     * @param  mixed  $default
     * @return mixed
     */
    public function get($key, $default = null);

    /**
     * Gets a package value.
     *
     * @param string $key
     * @param mixed  $value
     */
    public function set($key, $value);

    /**
     * Gets the name.
     */
    public function getName(): string;

    /**
     * Gets the type.
     */
    public function getType(): string;
}
