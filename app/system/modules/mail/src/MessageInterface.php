<?php

namespace Pagekit\Mail;

interface MessageInterface
{
    /**
     * Gets the mailer instance.
     */
    public function getMailer(): ?MailerInterface;

    /**
     * Sets the mailer instance.
     */
    public function setMailer(MailerInterface $mailer): self;

    /**
     * Sends the message.
     *
     * @param  array $errors
     */
    public function send(&$errors = null): int;

    /**
     * Queues the message for later sending.
     *
     * @param  array $errors
     */
    public function queue(&$errors = null): int;
}
