<?php

namespace Pagekit\Feed\Item;

use Pagekit\Feed\ItemInterface;
use Pagekit\Feed\Feed;
use Pagekit\Feed\Item;

class Atom extends Item
{
    /**
     * {@inheritdoc}
     */
    public function setId($id): ItemInterface
    {
        return $this->setElement('id', Feed\Atom::uuid($id, 'urn:uuid:'));
    }

    /**
     * {@inheritdoc}
     */
    public function setElement($name, $value, $attributes = null): ItemInterface
    {
        return parent::setElement($this->removeNamespace($name), $value, $attributes);
    }

    /**
     * @param  string $name
     */
    protected function removeNamespace($name): string
    {
        return 0 === strpos($name, 'atom:') ? substr($name, 5) : $name;
    }

    /**
     * {@inheritdoc}
     */
    public function setDescription($description): ItemInterface
    {
        return $this->setElement('summary', $description);
    }

    /**
     * {@inheritdoc}
     */
    public function setContent($content): ItemInterface
    {
        return $this->setElement('content', $content, ['type' => 'html']);
    }

    /**
     * {@inheritdoc}
     */
    public function setDate(\DateTimeInterface $date): ItemInterface
    {
        return $this->setElement('updated', date(\DATE_ATOM, $date->getTimestamp()));
    }

    /**
     * {@inheritdoc}
     */
    public function setAuthor($name, $email = null, $uri = null): ItemInterface
    {
        return $this->setElement('author', array_filter(compact('name', 'email', 'uri')));
    }

    /**
     * {@inheritdoc}
     */
    public function setLink($link): ItemInterface
    {
        return $this
            ->setElement('link', '', ['href' => $link])
            ->setId($link);
    }

    /**
     * {@inheritdoc}
     */
    public function addEnclosure($url, $length, $type, $multiple = true): ItemInterface
    {
        return $this->addElement('atom:link', '', [
            'length' => $length,
            'type'   => $type,
            'href'   => $url,
            'rel'    => 'enclosure'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function addElement($name, $value, $attributes = null): ItemInterface
    {
        return parent::addElement($this->removeNamespace($name), $value, $attributes);
    }
}
