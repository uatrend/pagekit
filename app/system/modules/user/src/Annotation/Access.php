<?php

namespace Pagekit\User\Annotation;

/**
 * @Annotation
 */
class Access
{
    protected ?string $expression = null;

    protected ?bool $admin = null;

    /**
     * Constructor.
     *
     * @param  array $data
     * @throws \BadMethodCallException
     */
    public function __construct(array $data)
    {
        foreach ($data as $key => $value) {

            if ($key == 'value') {
                $key = 'expression';
            }

            if (!method_exists($this, $method = 'set'.$key)) {
                throw new \BadMethodCallException(sprintf("Unknown property '%s' on annotation '%s'.", $key, get_class($this)));
            }

            $this->$method($value);
        }
    }

    /**
     * Gets the access expression.
     */
    public function getExpression(): ?string
    {
        return $this->expression;
    }

    /**
     * Sets the access expression.
     *
     * @param string
     */
    public function setExpression($expression): void
    {
        $this->expression = $expression;
    }

    /**
     * Gets admin option.
     */
    public function getAdmin(): ?bool
    {
        return $this->admin;
    }

    /**
     * Sets the admin option.
     *
     * @param bool $admin
     */
    public function setAdmin($admin): void
    {
        $this->admin = $admin;
    }
}
