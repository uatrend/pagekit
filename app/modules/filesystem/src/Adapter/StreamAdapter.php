<?php

namespace Pagekit\Filesystem\Adapter;

class StreamAdapter extends FileAdapter
{
    protected string $wrapper;

    /**
     * Constructor.
     *
     * @param string $path;
     * @param string $url;
     * @param string $wrapper;
     */
    public function __construct($path, $url = '', $wrapper = 'Pagekit\Filesystem\StreamWrapper')
    {
        parent::__construct($path, $url);

        $this->wrapper = $wrapper;
    }

    /**
     * {@inheritdoc}
     */
    public function getStreamWrapper(): string
    {
        return $this->wrapper;
    }

    /**
     * {@inheritdoc}
     */
    public function getPathInfo(array $info): array
    {
        $info['root'] = '';

        return parent::getPathInfo($info);
    }
}
