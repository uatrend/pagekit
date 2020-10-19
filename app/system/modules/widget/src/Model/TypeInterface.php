<?php

namespace Pagekit\Widget\Model;

interface TypeInterface extends \JsonSerializable
{
    /**
     * Renders the widget.
     *
     * @param  Widget $widget
     */
    public function render(Widget $widget): string;
}
