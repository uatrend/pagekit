<?php

namespace Pagekit\Twig;

use Twig\Cache\FilesystemCache;

class TwigCache extends FilesystemCache
{
    protected $dir;

    /**
     * {@inheritdoc}
     */
    public function __construct($directory, $options = 0)
    {
        $this->dir = $directory;

        parent::__construct($directory, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function generateKey(string $name, string $className): string
    {
        return $this->dir.'/'.sha1($className).'.twig.cache';
    }
}
