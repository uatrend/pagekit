<?php

namespace Pagekit\View\Asset;

use Pagekit\Application as App;

class FileLocatorAsset extends FileAsset
{
    /**
     * {@inheritdoc}
     */
    public function getSource(): string
    {
        if (!($path = $this->getPath())) {
            return parent::getSource();
        }

        $path = App::file()->getUrl($path);

        if ($version = $this->getOption('version')) {
            $path .= (false === strpos($path, '?') ? '?' : '&') . 'v=' . $version;
        }

        return $path;
    }

    /**
     * {@inheritdoc}
     */
    public function getPath(): string
    {
        return App::locator()->get($this->source) ?: false;
    }
}
