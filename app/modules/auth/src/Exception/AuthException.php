<?php

namespace Pagekit\Auth\Exception;

use Pagekit\Auth\UserInterface;

class AuthException extends \Exception
{
    protected ?\Pagekit\Auth\UserInterface $user = null;

    /**
     * Get the user.
     */
    public function getUser(): ?\Pagekit\Auth\UserInterface
    {
        return $this->user;
    }

    /**
     * Set the user.
     *
     * @param UserInterface $user
     */
    public function setUser(UserInterface $user): void
    {
        $this->user = $user;
    }
}
