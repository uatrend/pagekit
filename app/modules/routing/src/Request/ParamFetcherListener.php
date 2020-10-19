<?php

namespace Pagekit\Routing\Request;

use Pagekit\Event\EventSubscriberInterface;

class ParamFetcherListener implements EventSubscriberInterface
{
    protected \Pagekit\Routing\Request\ParamFetcherInterface $paramFetcher;

    /**
     * Constructor.
     *
     * @param ParamFetcherInterface $paramFetcher
     */
    public function __construct(ParamFetcherInterface $paramFetcher = null)
    {
        $this->paramFetcher = $paramFetcher ?: new ParamFetcher;
    }

    /**
     * Maps the parameters to request attributes.
     *
     * @param $event
     */
    public function onController($event, $request): void
    {
        $controller = $event->getController();
        $attributes = $request->attributes->get('_request', []);
        $parameters = isset($attributes['value']) ? $attributes['value'] : false;
        $options = isset($attributes['options']) ? $attributes['options'] : [];

        if (is_array($controller) && $parameters) {

            $this->paramFetcher->setRequest($request);
            $this->paramFetcher->setParameters($parameters, $options);

            $r = new \ReflectionMethod($controller[0], $controller[1]);

            foreach ($r->getParameters() as $index => $param) {
                if (null !== $value = $this->paramFetcher->get($index)) {
                    $request->attributes->set($param->getName(), $value);
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function subscribe(): array
    {
        return [
            'controller' => ['onController', 110]
        ];
    }
}
