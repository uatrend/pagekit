<?php

namespace Pagekit\Filter;

/**
 * This filter converts the value to float.
 */
class FloatFilter extends AbstractFilter
{
    /**
     * {@inheritdoc}
     */
    public function filter($value): float
    {
        return floatval((string) $value);
    }
}
