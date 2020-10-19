<?php

namespace Pagekit\View\Event;

use Pagekit\Event\Event;

class ViewEvent extends Event
{
    protected ?string $template = null;

    protected ?string $result = null;

    /**
     * Constructor.
     *
     * @param string $name
     * @param string $template
     * @param array  $parameters
     */
    public function __construct($name, $template, array $parameters = [])
    {
        parent::__construct($name, $parameters);

        $this->template = $template;
    }

    public function getTemplate(): ?string
    {
        return $this->template;
    }

    /**
     * @param string $template
     */
    public function setTemplate($template): void
    {
        $this->template = $template;
    }

    public function getResult(): ?string
    {
        return $this->result;
    }

    /**
     * @param string $result
     */
    public function setResult($result): void
    {
        $this->result = $result;
    }

    /**
     * @param string $result
     */
    public function addResult($result): void
    {
        $this->result .= $result;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->result;
    }
}
