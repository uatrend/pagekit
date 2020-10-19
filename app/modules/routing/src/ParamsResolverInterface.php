<?php

namespace Pagekit\Routing;

interface ParamsResolverInterface
{
    /**
     * Callback to modify parameters after route matching.
     *
     * @param  array $parameters
     */
    public function match(array $parameters = []): array;

    /**
     * Callback to modify parameters during URL generation.
     *
     * @param  array $parameters
     */
    public function generate(array $parameters = []): array;
}
