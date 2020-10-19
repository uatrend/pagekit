<?php

namespace Pagekit\Feed\Item;

use Pagekit\Feed\ItemInterface;
use Pagekit\Feed\Feed;
use Pagekit\Feed\Item;

class RSS1 extends Item
{
    /**
     * {@inheritdoc}
     */
    public function setId($id): ItemInterface
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setDescription($description): ItemInterface
    {
        return $this->setElement('description', $description);
    }

    /**
     * {@inheritdoc}
     */
    public function setDate(\DateTimeInterface $date): ItemInterface
    {
        return $this->setElement('dc:date', date('Y-m-d', $date->getTimestamp()));
    }

    /**
     * {@inheritdoc}
     */
    public function setAuthor($author, $email = null, $uri = null): ItemInterface
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setLink($link): ItemInterface
    {
        return $this->setElement('link', $link);
    }

    /**
     * {@inheritdoc}
     */
    public function addEnclosure($url, $length, $type, $multiple = true): ItemInterface
    {
        return $this;
    }
}
