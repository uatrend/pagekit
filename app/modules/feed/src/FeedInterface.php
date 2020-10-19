<?php

namespace Pagekit\Feed;

use Pagekit\Feed\ItemInterface;

interface FeedInterface
{
    public function getMimeType(): string;

    /**
     * @param string $mime
     */
    public function setMimeType($mime);

    /**
     * Adds an XML namespace.
     *
     * @param  string $prefix
     * @param  string $uri
     */
    public function addNamespace($prefix, $uri): self;

    /**
     * Gets the encoding.
     */
    public function getEncoding(): string;

    /**
     * Sets the encoding.
     *
     * @param  string $encoding
     */
    public function setEncoding($encoding): self;

    /**
     * Gets properties to be enclosed in CDATA.
     *
     * @return string[]
     */
    public function getCDATA(): array;

    /**
     * Adds properties to enclose in CDATA.
     *
     * @param   string[] $properties
     */
    public function addCDATA(array $properties): self;

    /**
     * @param  array $elements
     */
    public function createItem(array $elements = []): ItemInterface;

    /**
     * Adds an item.
     *
     * @param  ItemInterface $item
     */
    public function addItem(ItemInterface $item): self;

    /**
     * Sets the title.
     *
     * @param  string $title
     */
    public function setTitle($title): self;

    /**
     * Sets the link.
     *
     * @param  string $link
     */
    public function setLink($link): self;

    /**
     * Sets the image.
     *
     * @param  string $title
     * @param  string $link
     * @param  string $url
     */
    public function setImage($title, $link, $url): self;

    /**
     * Sets the description.
     *
     * @param  string $description
     */
    public function setDescription($description): self;

    /**
     * Sets a link with rel="self".
     *
     * @param  string $href
     */
    public function setSelfLink($href): self;

    /**
     * Sets a custom link.
     *
     * @param  string $href
     * @param  string $rel
     * @param  string $type
     * @param  string $hreflang
     * @param  string $title
     * @param  int    $length
     */
    public function setAtomLink($href, $rel = '', $type = '', $hreflang = '', $title = '', $length = 0): self;

    /**
     * Generates the feed.
     */
    public function generate(): string;

    /**
     * Outputs the feed.
     */
    public function output();
}
