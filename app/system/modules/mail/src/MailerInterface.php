<?php

namespace Pagekit\Mail;

interface MailerInterface
{
    /**
     * Creates a new message instance.
     *
     * @param  string $subject
     * @param  string $body
     * @param  mixed  $to
     * @param  mixed  $from
     */
    public function create($subject = null, $body = null, $to = null, $from = null): object;

    /**
     * Sends the given message.
     *
     * @param  mixed $message
     * @param  array $errors
     */
    public function send($message, &$errors = []): int;

    /**
     * Queues the given message and send it later.
     *
     * @param  mixed $message
     * @param  array $errors
     */
    public function queue($message, &$errors = []): int;

    /**
     * Registers a plugin.
     *
     * @param object $plugin
     */
    public function registerPlugin($plugin);
}
