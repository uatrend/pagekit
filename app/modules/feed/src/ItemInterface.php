<?php

namespace Pagekit\Feed;

interface ItemInterface
{
    /**
     * Sets the id.
     *
     * @param  string $id
     */
    public function setId($id): self;

    /**
     * Sets the title.
     *
     * @param  string $title
     */
    public function setTitle($title): self;

    /**
     * Sets the description.
     *
     * @param  string $description
     */
    public function setDescription($description): self;

    /**
     * Sets the content.
     *
     * @param  string $content
     */
    public function setContent($content): self;

    /**
     * Sets the date.
     *
     * @param  \DateTimeInterface $date
     */
    public function setDate(\DateTimeInterface $date): self;

    /**
     * Sets the author.
     *
     * @param  string $author
     * @param  string $email
     * @param  string $uri
     */
    public function setAuthor($author, $email = null, $uri = null): self;

    /**
     * Sets the link.
     *
     * @param  string $link
     */
    public function setLink($link): self;

    /**
     * Adds an attachment.
     *
     * @param  string  $url
     * @param  integer $length
     * @param  string  $type
     * @param  bool    $multiple
     */
    public function addEnclosure($url, $length, $type, $multiple = true): self;

    /**
     * Gets the items elements.
     *
     * @return array[]
     */
    public function getElements(): array;
}
