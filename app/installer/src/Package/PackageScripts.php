<?php

namespace Pagekit\Installer\Package;

use Pagekit\Application as App;

class PackageScripts
{
    protected ?string $file = null;

    protected ?string $current = null;

    /**
     * Constructor.
     *
     * @param string $file
     * @param string $current
     */
    public function __construct($file, $current = null)
    {
        $this->file = $file;
        $this->current = $current;
    }

    /**
     * Runs the script's install hook.
     */
    public function install(): void
    {
        $this->run($this->get('install'));
    }

    /**
     * Runs the script's uninstall hook.
     */
    public function uninstall(): void
    {
        $this->run($this->get('uninstall'));
    }

    /**
     * Runs the script's enable hook.
     */
    public function enable(): void
    {
        $this->run($this->get('enable'));
    }

    /**
     * Runs the script's disable hook.
     */
    public function disable(): void
    {
        $this->run($this->get('disable'));
    }

    /**
     * Runs the script's update hooks.
     */
    public function update(): void
    {
        $this->run($this->getUpdates());
    }

    /**
     * Checks for script updates.
     */
    public function hasUpdates(): bool
    {
        return (bool) $this->getUpdates();
    }

    /**
     * @param  string $name
     */
    protected function get($name): array
    {
        $scripts = $this->load();

        return isset($scripts[$name]) ? (array) $scripts[$name] : [];
    }

    /**
     * @return array
     */
    protected function load()
    {
        if (!$this->file || !file_exists($this->file)) {
            return [];
        }

        return require $this->file;
    }

    /**
     * @param array|callable $scripts
     */
    protected function run($scripts): void
    {
        array_map(function ($script) {

            if (is_callable($script)) {
                call_user_func($script, App::getInstance());
            }

        }, (array) $scripts);
    }

    /**
     * @return callable[]
     */
    protected function getUpdates(): array
    {
        $updates = $this->get('updates');

        $versions = array_filter(array_keys($updates), fn($version) => version_compare($version, $this->current, '>'));

        $updates = array_intersect_key($updates, array_flip($versions));
        uksort($updates, 'version_compare');

        return $updates;
    }
}
