<?php

namespace Pagekit\Filesystem\Adapter;

interface AdapterInterface
{
    /**
     * Gets stream wrapper classname.
     */
    public function getStreamWrapper(): ?string;

    /**
     * Gets file path info.
     *
     * @param  array $info
     */
    public function getPathInfo(array $info): array;
}
