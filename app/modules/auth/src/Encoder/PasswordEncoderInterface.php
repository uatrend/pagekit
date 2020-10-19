<?php

namespace Pagekit\Auth\Encoder;

interface PasswordEncoderInterface
{
    /**
     * Encodes the raw password.
     *
     * @param  string $raw  The password to hash
     */
    public function hash($raw): string;

    /**
     * Checks a raw password against an encoded password.
     *
     * @param  string $hash A hashed password
     * @param  string $raw  A raw password
     * @param  string $salt
     */
    public function verify($hash, $raw, $salt = null): bool;
}
