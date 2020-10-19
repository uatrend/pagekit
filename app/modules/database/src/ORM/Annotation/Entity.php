<?php

namespace Pagekit\Database\ORM\Annotation;

/**
 * @Annotation
 * @Target("CLASS")
 */
final class Entity implements Annotation
{
    public string $tableClass;

    public string $eventPrefix = '';
}
