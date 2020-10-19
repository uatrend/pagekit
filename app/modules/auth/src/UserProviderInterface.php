<?php

namespace Pagekit\Auth;

use Pagekit\Auth\UserInterface;

interface UserProviderInterface
{
    /**
     * Retrieves a user by its unique identifier.
     *
     * @param  string $id
     */
    public function find($id): ?UserInterface;

    /**
     * Retrieves a user by their unique username.
     *
     * @param  string $username
     */
    public function findByUsername($username): ?UserInterface;
    
    /**
     * Retrieves a user by the given credentials.
     *
     * @param  array $credentials
     */
    public function findByCredentials(array $credentials): ?UserInterface;

    /**
     * Validates a user against the given credentials.
     *
     * @param  UserInterface $user
     * @param  array         $credentials
     */
    public function validateCredentials(UserInterface $user, array $credentials): bool;
}
