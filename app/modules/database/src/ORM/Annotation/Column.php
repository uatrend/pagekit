<?php

namespace Pagekit\Database\ORM\Annotation;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
final class Column implements Annotation
{
    public string $name = '';

    /** @var mixed */
    public $type = 'string';
}
