<?php

namespace Pagekit\Event;

class PrefixEventDispatcher implements EventDispatcherInterface
{
    protected string $prefix = '';

    protected \Pagekit\Event\EventDispatcherInterface $events;

    /**
     * Constructor.
     *
     * @param  string                   $prefix
     * @param  EventDispatcherInterface $events
     */
    public function __construct($prefix, EventDispatcherInterface $events = null)
    {
        $this->prefix = $prefix;
        $this->events = $events ?: new EventDispatcher();
    }

    /**
     * {@inheritdoc}
     */
    public function on($event, $listener, $priority = 0): void
    {
        $this->events->on($this->prefix.$event, $listener, $priority);
    }

    /**
     * {@inheritdoc}
     */
    public function off($event, $listener = null): void
    {
        $this->events->off($this->prefix.$event, $listener);
    }

    /**
     * {@inheritdoc}
     */
    public function subscribe(EventSubscriberInterface $subscriber): void
    {
        $this->events->subscribe($subscriber);
    }

    /**
     * {@inheritdoc}
     */
    public function unsubscribe(EventSubscriberInterface $subscriber): void
    {
        $this->events->unsubscribe($subscriber);
    }

    /**
     * {@inheritdoc}
     */
    public function trigger($event, array $arguments = []): EventInterface
    {
        if (is_string($event)) {
            $event = $this->prefix.$event;
        } elseif ($event instanceof EventInterface) {
            $event->setName($this->prefix.$event->getName());
        }

        return $this->events->trigger($event, $arguments);
    }

    /**
     * {@inheritdoc}
     */
    public function hasListeners($event = null): bool
    {
        return $this->events->hasListeners($event);
    }

    /**
     * {@inheritdoc}
     */
    public function getListeners($event = null): array
    {
        return $this->events->getListeners($event);
    }

    /**
     * {@inheritdoc}
     */
    public function getListenerPriority($event, $listener): ?int
    {
        return $this->events->getListenerPriority($event, $listener);
    }

    /**
     * {@inheritdoc}
     */
    public function getEventClass(): string
    {
        return $this->events->getEventClass();
    }
}
