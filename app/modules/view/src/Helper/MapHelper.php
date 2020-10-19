<?php

namespace Pagekit\View\Helper;

use Pagekit\View\View;

class MapHelper implements HelperInterface, \IteratorAggregate
{
    protected array $map = [];

    /**
     * {@inheritdoc}
     */
    public function register(View $view): void
    {
        $view->on('render', function ($event) {
            if ($this->has($name = $event->getTemplate())) {
                $event->setTemplate($this->get($name));
            }
        }, 10);
    }

    /**
     * Add shortcut.
     *
     * @see add()
     */
    public function __invoke($name, $path = null)
    {
        $this->add($name, $path);
    }

    /**
     * Gets a template.
     *
     * @param  string $name
     */
    public function get($name): ?string
    {
        return isset($this->map[$name]) ? $this->map[$name] : null;
    }

    /**
     * Adds a template.
     *
     * @param string|array $name
     * @param string       $path
     */
    public function add($name, $path = null): void
    {
        if (is_string($name) && $path) {
            $this->map[$name] = $path;
        } elseif (is_array($name)) {
            foreach ($name as $key => $path) {
                $this->map[$key] = $path;
            }
        }
    }

    /**
     * Checks if the template exists.
     *
     * @param  string $name
     */
    public function has($name): bool
    {
        return isset($this->map[$name]);
    }

    /**
     * Implements the IteratorAggregate.
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->map);
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'map';
    }
}
