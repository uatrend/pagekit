<?php

namespace Pagekit\Mail;

use Pagekit\Mail\MailerInterface;
use Pagekit\Mail\Message;
use Swift_Attachment;
use Swift_Image;
use Swift_Message;
use Swift_Mime_Attachment;

class Message extends Swift_Message implements MessageInterface
{
    protected ?MailerInterface $mailer = null;

    /**
     * {@inheritdoc}
     */
    public function getMailer(): ?MailerInterface
    {
        return $this->mailer;
    }

    /**
     * {@inheritdoc}
     */
    public function setMailer(MailerInterface $mailer): self
    {
        $this->mailer = $mailer;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function send(&$errors = null): int
    {
        return $this->mailer->send($this, $errors);
    }

    /**
     * {@inheritdoc}
     */
    public function queue(&$errors = null): int
    {
        return $this->mailer->queue($this, $errors);
    }

    /**
     * Attaches a file to the message.
     *
     * @param  string $file
     * @param  string $name
     * @param  string $mime
     */
    public function attachFile($file, $name = null, $mime = null): Message
    {
		return $this->prepareAttachment(Swift_Attachment::fromPath($file), $name, $mime);
    }

    /**
     * Attaches in-memory data as an attachment.
     *
     * @param  string $data
     * @param  string $name
     * @param  string $mime
     */
    public function attachData($data, $name, $mime = null): Message
    {
        return $this->prepareAttachment(Swift_Attachment::newInstance($data, $name), null, $mime);
    }

    /**
     * Embeds a file in the message and get the CID.
     *
     * @param  string $file
     * @param  string $cid
     */
    public function embedFile($file, $cid = null): string
    {
        $attachment = Swift_Image::fromPath($file);

        if ($cid) {
            $attachment->setId(strpos($cid, 'cid:') === 0 ? $cid : 'cid:'.$cid);
        }

        return $this->embed($attachment);
    }

    /**
     * Embeds in-memory data in the message and get the CID.
     *
     * @param  string $data
     * @param  string $name
     * @param  string $contentType
     */
    public function embedData($data, $name, $contentType = null): string
    {
		return $this->embed(Swift_Image::newInstance($data, $name, $contentType));
    }

	/**
  * Prepare and attach the given attachment.
  *
  * @param  Swift_Mime_Attachment $attachment
  * @param  string                $name
  * @param  string                $mime
  */
 protected function prepareAttachment(Swift_Mime_Attachment $attachment, $name = null, $mime = null): self
	{
		if (null !== $mime) {
			$attachment->setContentType($mime);
		}

		if (null !== $name) {
			$attachment->setFilename($name);
		}

		$this->attach($attachment);

		return $this;
	}
}
