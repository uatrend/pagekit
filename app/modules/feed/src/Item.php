<?php

namespace Pagekit\Feed;

abstract class Item implements ItemInterface
{
    use ElementsTrait;

    /**
     * {@inheritdoc}
     */
    public function setTitle($title): ItemInterface
    {
        return $this->setElement('title', $title);
    }

    /**
     * {@inheritdoc}
     */
    public function setContent($content): ItemInterface
    {
        return $this;
    }
}
