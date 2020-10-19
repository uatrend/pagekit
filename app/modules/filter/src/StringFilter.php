<?php

namespace Pagekit\Filter;

/**
 * This filter converts the value to string.
 */
class StringFilter extends AbstractFilter
{
    /**
     * {@inheritdoc}
     */
    public function filter($value): string
    {
        return (string) $value;
    }
}
