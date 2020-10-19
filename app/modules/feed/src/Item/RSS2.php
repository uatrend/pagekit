<?php

namespace Pagekit\Feed\Item;

use Pagekit\Feed\ItemInterface;
use Pagekit\Feed\Feed;
use Pagekit\Feed\Item;

class RSS2 extends Item
{
    /**
     * {@inheritdoc}
     */
    public function setId($id): ItemInterface
    {
        return $this->addElement('guid', $id, ['isPermaLink' => 'true']);
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
        return $this->setElement('pubDate', date(\DATE_RSS, $date->getTimestamp()));
    }

    /**
     * {@inheritdoc}
     */
    public function setAuthor($author, $email = null, $uri = null): ItemInterface
    {
        return $this->setElement('author', $email ? $email.' ('.$author.')' : $author);
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
        return $this->addElement('enclosure', '', compact('url', 'length', 'type'));
    }
}
