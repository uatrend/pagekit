<?php

namespace Pagekit\Routing;

interface ResourceInterface extends \Serializable
{
    /**
     * Gets the resources modified time.
     */
    public function getModified(): int;
}
