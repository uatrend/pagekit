<?php

namespace Pagekit\Mail\Plugin;

class ImpersonatePlugin implements \Swift_Events_SendListener
{
    protected string $address;
    protected string $name;

    /**
     * Constructor.
     *
     * @param string $address
     * @param string $name
     */
    public function __construct($address, $name)
    {
        $this->address = $address;
        $this->name    = $name;
    }

    /**
     * Invoked immediately before the Message is sent.
     *
     * @param \Swift_Events_SendEvent $event
     */
    public function beforeSendPerformed(\Swift_Events_SendEvent $event): void
    {
        $event->getMessage()->setFrom($this->address, $this->name);
    }

    /**
     * Invoked immediately after the Message is sent.
     *
     * @param \Swift_Events_SendEvent $event
     */
    public function sendPerformed(\Swift_Events_SendEvent $event): void
    {
    }
}
