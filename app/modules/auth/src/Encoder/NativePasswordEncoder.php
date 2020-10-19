<?php

namespace Pagekit\Auth\Encoder;

class NativePasswordEncoder implements PasswordEncoderInterface
{
    protected int $cost = 10;
    protected string $algorithm = PASSWORD_BCRYPT;

    /**
     * {@inheritdoc}
     */
    public function hash($raw): string
    {
        $options = ['cost' => $this->cost];

        return password_hash($raw, $this->algorithm, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function verify($hash, $raw, $salt = null): bool
    {
        if (null !== $salt) {
            throw new \InvalidArgumentException('The salt needs to be included with the hash.');
        }

        return password_verify($raw, $hash);
    }
}
