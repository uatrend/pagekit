<?php

namespace Pagekit\Database\ORM\Annotation;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
final class HasOne implements Annotation
{
    public string $targetEntity;

    public string $keyFrom;

    public string $keyTo;
}
