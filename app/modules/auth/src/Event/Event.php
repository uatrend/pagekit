<?php

namespace Pagekit\Auth\Event;

use Pagekit\Auth\UserInterface;
use Pagekit\Event\Event as BaseEvent;

class Event extends BaseEvent
{
    protected ?\Pagekit\Auth\UserInterface $user = null;

    /**
     * Constructor.
     *
     * @param string $name	 
     * @param UserInterface $user
     */
    public function __construct($name, UserInterface $user = null)
    {
        parent::__construct($name);

        $this->user = $user;
    }

    /**
     * Gets the user.
     */
    public function getUser(): ?\Pagekit\Auth\UserInterface
    {
        return $this->user;
    }
}
