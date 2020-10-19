<?php

namespace Pagekit\Captcha\Annotation;

/**
 * @Annotation
 */
class Captcha
{
    protected ?string $route = null;

    protected bool $verify = false;

    /**
     * Constructor.
     *
     * @param  array $data
     * @throws \BadMethodCallException
     */
    public function __construct(array $data)
    {
        foreach ($data as $key => $value) {

            if (!method_exists($this, $method = 'set'.$key)) {
                throw new \BadMethodCallException(sprintf("Unknown property '%s' on annotation '%s'.", $key, get_class($this)));
            }

            $this->$method($value);
        }
    }

    /**
     * Gets the captcha route.
     */
    public function getRoute(): ?string
    {
        return $this->route;
    }

    /**
     * Sets the captcha route.
     *
     * @param string
     */
    public function setRoute($route): void
    {
        $this->route = $route;
    }

    /**
     * Gets verify option.
     */
    public function getVerify(): bool
    {
        return $this->verify;
    }

    /**
     * Sets the verify option.
     *
     * @param bool $verify
     */
    public function setVerify($verify): void
    {
        $this->verify = $verify;
    }
}
