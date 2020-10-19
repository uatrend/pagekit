<?php

namespace Pagekit\Auth;

interface UserInterface
{
    /**
     * Retrieves the unique identifier
     *
     * @return string Id
     */
    public function getId(): string;

    /**
     * Retrieves the username
     *
     * @return string Username
     */
    public function getUsername(): string;

    /**
     * Retrieves the password
     *
     * @return string Password
     */
    public function getPassword(): string;
}
