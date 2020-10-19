<?php

namespace Pagekit\Database\ORM\Relation;

use Pagekit\Database\ORM\EntityManager;
use Pagekit\Database\ORM\Metadata;
use Pagekit\Database\ORM\QueryBuilder;

/**
 * The Relation class handles relations between entities.
 */
abstract class Relation
{
    /**
     * The entity manager
     */
    protected EntityManager $manager;

    /**
     * The parent entity metadata
     */
    protected Metadata $metadata;

    /**
     * The name of the relationship in the parent entity
     */
    protected string $name;

    /**
     * The classname of the target entity
     */
    protected string $targetEntity;

    /**
     * The primary key of source entity
     */
    protected string $keyFrom;

    /**
     * The foreign key of target entity
     */
    protected string $keyTo;

    /**
     * The target metadata
     */
    protected Metadata $targetMetadata;

    /**
     * Constructor.
     *
     * @param  EntityManager $manager
     * @param  Metadata      $metadata
     * @param  array         $mapping
     */
    public function __construct(EntityManager $manager, Metadata $metadata, array $mapping)
    {
        $this->manager  = $manager;
        $this->metadata = $metadata;

        if (!$this->name = $mapping['name']) {
            throw new \InvalidArgumentException('The parameter "name" may not be omitted in relations.');
        }
        $this->targetEntity   = $mapping['targetEntity'];
        $this->targetMetadata = $manager->getMetadata($mapping['targetEntity']);
    }

    /**
     * Resolves the entity relation.
     *
     * @param array        $entities
     * @param QueryBuilder $query
     */
    abstract public function resolve(array $entities, QueryBuilder $query);

    /**
     * Initialize the relationship
     *
     * @param array $entities
     * @param mixed $default
     */
    protected function initRelation(array $entities, $default = false)
    {
        foreach ($entities as $entity) {
            $this->metadata->setValue($entity, $this->name, $default);
        }
    }

    /**
     * Gets the related keys
     *
     * @param  array    $entities
     * @param  string   $key
     */
    protected function getKeys(array $entities, $key = null): array {

        $key  = $key ?: $this->keyFrom;
        $keys = [];

        foreach ($entities as $entity) {
            if ($value = $this->metadata->getValue($entity, $key, true)) {
                $keys[] = $value;
            }
        }

        return array_unique($keys);
    }

    /**
     * Map targets to entities
     *
     * @param array    $entities
     * @param array    $targets
     */
    protected function map($entities, $targets)
    {
        $identifier = $this->targetMetadata->getIdentifier();

        foreach ($targets as $target) {

            $id = $this->targetMetadata->getValue($target, $this->keyTo, true);

            foreach ($entities as $entity) {
                if ($id == $this->metadata->getValue($entity, $this->keyFrom, true)) {
                    $value = $this->metadata->getValue($entity, $this->name);

                    if (is_array($value)) {
                        $value[$this->targetMetadata->getValue($target, $identifier)] = $target;
                    } else {
                        $value = $target;
                    }

                    $this->metadata->setValue($entity, $this->name, $value);
                }
            }
        }
    }

    /**
     * Resolve additional relations
     *
     * @param QueryBuilder $query
     * @param array        $targets
     */
    protected function resolveRelations(QueryBuilder $query, $targets): void
    {
        if (!$targets) {
            return;
        }

        foreach ($query->getRelations() as $name => $q) {
            $this->manager->related($targets, $name, $q);
        }
    }
}
