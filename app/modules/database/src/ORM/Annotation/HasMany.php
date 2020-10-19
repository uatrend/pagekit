<?php

namespace Pagekit\Database\ORM\Annotation;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
final class HasMany implements Annotation
{
    public string $targetEntity;

    public string $keyFrom;

    public string $keyTo;

    public array $orderBy = [];
}
