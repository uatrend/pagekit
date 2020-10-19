<?php

namespace Pagekit\Feed;

trait ElementsTrait
{
    /**
     * @var array[]
     */
    protected array $elements;

    /**
     * {@inheritdoc}
     */
    public function setElement($name, $value, $attributes = null)
    {
        unset($this->elements[$name]);
        return $this->addElement($name, $value, $attributes);
    }

    /**
     * {@inheritdoc}
     */
    public function addElement($name, $value, $attributes = null): object
    {
        $this->elements[$name][] = [$name, $value, $attributes];
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addElements(array $elements): object
    {
        foreach ($elements as $name => $value) {
            if (method_exists($this, $method = 'set'.$name)) {
                if (is_array($value)) {
                    call_user_func_array([$this, $method], $value);
                } else {
                    $this->$method($value);
                }
            } else {
                $this->addElement($name, $value);
            }
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getElements(): array
    {
        return call_user_func_array('array_merge', $this->elements);
    }
}
