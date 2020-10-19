<?php

namespace Pagekit\Routing\Annotation;

/**
 * @Annotation
 */
class Request
{
    public array $data;

    /**
     * Constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Returns the data.
     */
    public function getData(): array
    {
        return $this->data;
    }
}
