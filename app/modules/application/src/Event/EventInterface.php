<?php

namespace Pagekit\Event;

interface EventInterface
{
    /**
     * Gets the event name.
     */
    public function getName(): string;

    /**
     * Gets the event dispatcher.
     */
    public function getDispatcher(): EventDispatcherInterface;

    /**
     * Is propagation stopped?
     */
    public function isPropagationStopped(): bool;

    /**
     * Stop further event propagation.
     */
    public function stopPropagation(): void;
}
