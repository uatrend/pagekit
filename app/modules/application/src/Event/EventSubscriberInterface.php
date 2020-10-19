<?php

namespace Pagekit\Event;

interface EventSubscriberInterface
{
    /**
     * Returns an array of event names this subscriber wants to listen to.
     */
    public function subscribe(): array;
}
