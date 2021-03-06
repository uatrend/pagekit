<?php

namespace Pagekit\Kernel\Event;

use Pagekit\Event\EventSubscriberInterface;

/**
 * @author Fabien Potencier <fabien@symfony.com>
 * @copyright Copyright (c) 2004-2015 Fabien Potencier
 */
class ResponseListener implements EventSubscriberInterface
{
    protected string $charset;

    /**
     * Constructor.
     *
     * @param string $charset
     */
    public function __construct($charset = 'UTF-8')
    {
        $this->charset = $charset;
    }

    /**
     * Filters the Response.
     *
     * @param $event
     * @param $request
     * @param $response
     */
    public function onResponse($event, $request, $response): void
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        if ($response->getCharset() === null) {
            $response->setCharset($this->charset);
        }

        $response->prepare($request);
    }

    public function subscribe(): array
    {
        return [
            'response' => ['onResponse', -10]
        ];
    }
}
