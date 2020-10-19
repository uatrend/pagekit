<?php

namespace Pagekit\Database\ORM;

use Doctrine\DBAL\Types\Type;
use Pagekit\Database\ORM\MetadataManager;

class Metadata
{
    protected MetadataManager $manager;

    protected string $class;

    protected string $table;

    protected string $identifier = '';

    protected array $fields = [];

    protected array $fieldNames = [];

    protected array $relations = [];

    protected bool $isMappedSuperclass = false;

    protected array $events = [];

    protected string $eventPrefix = '';

    protected ?\ReflectionClass $reflClass = null;

    /**
     * Constructor.
     *
     * @param MetadataManager $manager
     * @param string          $class
     * @param array           $config
     */
    public function __construct($manager, $class, array $config = [])
    {
        $this->manager = $manager;
        $this->class   = $class;

        $this->setConfig($config);
    }

    /**
     * Gets name of the entity class.
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * Gets the entity's reflection class.
     */
    public function getReflectionClass(): \ReflectionClass
    {
        if ($this->reflClass === null) {
            $this->reflClass = new \ReflectionClass($this->class);
        }

        return $this->reflClass;
    }

    /**
     * Gets the name of the table.
     */
    public function getTable(): string
    {
        return $this->table;
    }

    /**
     * Gets the field or column name of the identifier.
     *
     * @param  bool $column
     */
    public function getIdentifier($column = false): ?string
    {
        return $column ? $this->fieldNames[$this->identifier] : $this->identifier;
    }

    /**
     * Gets a field's mapping definitions.
     *
     * @param  string $name
     * @param  string $attribute
     * @return array|null
     */
    public function getField($name, $attribute = null)
    {
        if (isset($this->fields[$name])) {
            return $attribute !== null ? $this->fields[$name][$attribute] : $this->fields[$name];
        }
    }

    /**
     * Gets all field mapping definitions.
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * Gets all relation mappings of the class.
     */
    public function getRelationMappings(): array
    {
        return $this->relations;
    }

    /**
     * Gets the mapping of a relation.
     *
     * @param  string $name
     * @throws \Exception If no mapping is found for relation.
     */
    public function getRelationMapping($name): array
    {
        if (!isset($this->relations[$name])) {
            throw new \Exception(sprintf("No mapping found for relation '%s' on class '%s'.", $name, $this->class));
        }

        return $this->relations[$name];
    }

    /**
     * Is mapped super class.
     */
    public function isMappedSuperclass(): bool
    {
        return $this->isMappedSuperclass;
    }

    /**
     * Gets a field's value. The column parameter determines if the "name" is a field or column name.
     *
     * @param  object $entity
     * @param  string $name
     * @param  bool   $column
     * @param  bool   $convert
     * @return mixed
     */
    public function getValue($entity, $name, $column = false, $convert = false)
    {
        if ($column && isset($this->fieldNames[$name])) {
            $name = $this->fieldNames[$name];
        }

        if (!property_exists($entity, $name) && !isset($entity->$name)) {
            return null;
        }

        $value = $entity->$name;

        if ($convert) {
            $value = Type::getType($this->fields[$name]['type'])->convertToDatabaseValue($value, $this->manager->getConnection()->getDatabasePlatform());
        }

        return $value;
    }

    /**
     * Sets a field to a value. The column parameter determines if the "name" is a field or column name.
     *
     * @param object $entity
     * @param string $name
     * @param mixed  $value
     * @param bool   $column
     * @param bool   $convert
     */
    public function setValue($entity, $name, $value, $column = false, $convert = false): void
    {
        if ($column && isset($this->fieldNames[$name])) {
            $name = $this->fieldNames[$name];
        }

        if (!property_exists($entity, $name) && !isset($entity->$name)) {
            return;
        }

        if ($convert && isset($this->fields[$name])) {
            $value = Type::getType($this->fields[$name]['type'])->convertToPHPValue($value, $this->manager->getConnection()->getDatabasePlatform());
        }

        $entity->$name = $value;
    }

    /**
     * Gets all field values. The column parameter determines if the array keys are column names.
     *
     * @param  mixed $entity
     * @param  bool  $column
     * @param  bool  $convert
     */
    public function getValues($entity, $column = false, $convert = false): array
    {
        $data = [];

        foreach ($this->fields as $name => $field) {
            $key        = $column ? $field['column'] : $name;
            $data[$key] = $this->getValue($entity, $name, false, $convert);
        }

        return $data;
    }

    /**
     * Sets multiple field values. The column parameter determines if the array keys are column names.
     *
     * @param  mixed $entity
     * @param  array $values
     * @param  bool  $column
     * @param  bool  $convert
     */
    public function setValues($entity, array $values, $column = false, $convert = false): void
    {
        foreach ($values as $name => $value) {
            $this->setValue($entity, $name, $value, $column, $convert);
        }
    }

    /**
     * Gets the events.
     */
    public function getEvents(): array
    {
        return $this->events;
    }

    /**
     * Gets the event prefix.
     */
    public function getEventPrefix(): string
    {
        return $this->eventPrefix ?: strtolower($this->getReflectionClass()->getShortName());
    }

    /**
     * Creates a new instance of the mapped class, without invoking the constructor.
     */
    public function newInstance(): object
    {
        return $this->getReflectionClass()->newInstanceWithoutConstructor();
    }

    /**
     * Gets the config values.
     */
    public function getConfig(): array
    {
        return [
            'class' => $this->class,
            'eventPrefix' => $this->eventPrefix,
            'events' => $this->events,
            'fields' => $this->fields,
            'isMappedSuperclass' => $this->isMappedSuperclass,
            'relations' => $this->relations,
            'table' => $this->table
        ];
    }

    /**
     * Sets the config values and creates the reflection objects.
     *
     * @param array $config
     */
    protected function setConfig(array $config): void
    {
        if (isset($config['fields'])) {
            foreach (array_keys($config['fields']) as $name) {
                $this->validateField($config['fields'][$name]);
            }
        }

        if (isset($config['relations'])) {
            foreach (array_keys($config['relations']) as $name) {
                $this->validateRelation($config['relations'][$name]);
            }
        }

        foreach ($config as $property => $value) {
            $this->$property = $value;
        }
    }

    /**
     * Validate a field mapping.
     *
     * @param  array $field
     * @throws \Exception
     */
    protected function validateField(array &$field): void
    {
        if (!isset($field['name']) || strlen($field['name']) == 0) {
            throw new \Exception(sprintf("The field or association mapping misses the 'name' attribute in entity '%s'.", $this->class));
        }

        if (!isset($field['type'])) {
            $field['type'] = 'string';
        }

        if (!isset($field['column'])) {
            $field['column'] = $field['name'];
        }

        if (isset($this->fieldNames[$field['column']])) {
            throw new \Exception(sprintf("Duplicate definition of column '%s' on entity '%s' in a field.", $field['column'], $this->class));
        }

        $this->fieldNames[$field['column']] = $field['name'];

        if (isset($field['id']) && $field['id'] === true) {
            $this->identifier = $field['name'];
        }

        if (Type::hasType($field['type']) && Type::getType($field['type'])->canRequireSQLConversion()) {

            if (isset($field['id']) && $field['id'] === true) {
                throw new \Exception(sprintf("It is not possible to set id field '%s' to type '%s' in entity class '%s'. The type '%s' requires conversion SQL which is not allowed for identifiers.", $field['name'], $field['type'], $this->class, $field['type']));
            }

            $field['requireSQLConversion'] = true;
        }
    }

    /**
     * Validate a relation mapping.
     *
     * @param  array $relation
     * @throws \Exception
     */
    protected function validateRelation(array &$relation): void
    {
        if (isset($relation['targetEntity'])) {

            $namespace = $this->getReflectionClass()->getNamespaceName();

            if (strlen($namespace) > 0 && strpos($relation['targetEntity'], '\\') === false) {
                $relation['targetEntity'] = $namespace.'\\'.$relation['targetEntity'];
            }

            $relation['targetEntity'] = ltrim($relation['targetEntity'], '\\');
        }

        if (!isset($relation['name']) || strlen($relation['name']) == 0) {
            throw new \Exception(sprintf("Mandatory attribute 'name' is missing in entity class '%s' for relation type '%s'.", $this->class, $relation['type']));
        }

        if (!isset($relation['targetEntity'])) {
            throw new \Exception(sprintf("Mandatory attribute 'targetEntity' is missing for property '%s' in entity class '%s'.", $relation['name'], $this->class));
        }
    }
}
