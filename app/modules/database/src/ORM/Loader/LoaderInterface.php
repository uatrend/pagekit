<?php

namespace Pagekit\Database\ORM\Loader;

interface LoaderInterface
{
    /**
     * Loads the metadata config for a given class.
     *
     * @param  \ReflectionClass $class
     * @param  array            $config
     */
    public function load(\ReflectionClass $class, array $config = []): array;

    /**
     * A transient class is NOT annotated with either @Entity or @MappedSuperclass.
     *
     * @param  \ReflectionClass $class
     */
    public function isTransient(\ReflectionClass $class): bool;
}
