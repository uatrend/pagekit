<?php

namespace Pagekit\Filter;

/**
 * This filter converts the value to boolean.
 */
class BooleanFilter extends AbstractFilter
{
    /**
     * {@inheritdoc}
     */
    public function filter($value): bool
    {
        return (bool) @strval($value);
    }
}
