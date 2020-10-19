<?php

namespace Pagekit\Content\Event;

use Pagekit\Event\Event;

class ContentEvent extends Event
{
    protected string $content;

    protected array $plugins = [];

    /**
     * Constructor.
     *
     * @param string $name
     * @param string $content
     * @param array  $parameters
     */
    public function __construct($name, $content, array $parameters = [])
    {
        parent::__construct($name, $parameters);

        $this->content = $content;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent($content): void
    {
        $this->content = $content;
    }

    public function getPlugins(): array
    {
        return $this->plugins;
    }

    /**
     * @param  string $name
     * @return mixed
     */
    public function getPlugin($name)
    {
        return isset($this->plugins[$name]) ? $this->plugins[$name] : null;
    }

    /**
     * @param string $name
     * @param mixed  $callback
     */
    public function addPlugin($name, $callback): void
    {
        $this->plugins[$name] = $callback;
    }
}
