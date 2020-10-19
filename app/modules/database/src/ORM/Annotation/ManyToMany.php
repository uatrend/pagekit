<?php

namespace Pagekit\Database\ORM\Annotation;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
final class ManyToMany implements Annotation
{
    public string $targetEntity;

    public string $keyFrom;

    public string $keyTo;

    public string $keyThroughFrom;

    public string $tableThrough;

    public string $keyThroughTo;

    public array $orderBy = [];
}
