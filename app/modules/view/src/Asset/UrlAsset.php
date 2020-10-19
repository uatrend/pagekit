<?php

namespace Pagekit\View\Asset;

class UrlAsset extends Asset
{
    /**
     * {@inheritdoc}
     */
    public function hash($salt = ''): string
    {
        return hash('crc32b', $this->source . $salt);
    }
}
