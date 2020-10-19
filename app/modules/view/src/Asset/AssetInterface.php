<?php

namespace Pagekit\View\Asset;

interface AssetInterface
{
    /**
     * Gets the name.
     */
    public function getName(): string;

    /**
     * Gets the source.
     */
    public function getSource(): ?string;

    /**
     * Gets the path.
     */
    public function getPath(): string;

    /**
     * Gets the dependencies.
     */
    public function getDependencies(): array;

    /**
     * Gets the content.
     */
    public function getContent(): string;

    /**
     * Sets the content.
     *
     * @param string $content
     */
    public function setContent($content);

    /**
     * Gets all options.
     */
    public function getOptions(): array;

    /**
     * Gets a option.
     *
     * @param  string $name
     * @return mixed
     */
    public function getOption($name);

    /**
     * Sets a option.
     *
     * @param string $name
     * @param mixed $value
     */
    public function setOption($name, $value);

    /**
     * Gets the unique hash.
     *
     * @param  string $salt
     */
    public function hash($salt = ''): string;

    /**
     * Applies filters and returns the asset as a string.
     *
     * @param  array $filters
     */
    public function dump(array $filters = []): string;
}
