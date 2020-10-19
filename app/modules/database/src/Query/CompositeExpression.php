<?php

namespace Pagekit\Database\Query;

class CompositeExpression implements \Countable
{
    /**
     * Constant that represents an AND composite expression.
     */
    const TYPE_AND = 'AND';

    /**
     * Constant that represents an OR composite expression.
     */
    const TYPE_OR  = 'OR';

    /**
     * The instance type of composite expression.
     */
    protected string $type;

    /**
     * Each expression part of the composite expression.
     */
    protected array $parts = [];

    /**
     * Constructor.
     *
     * @param string $type
     * @param array  $parts
     */
    public function __construct($type, array $parts = [])
    {
        $this->type = $type;
        $this->addMultiple($parts);
    }

    /**
     * Returns the type of this composite expression (AND/OR).
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Adds an expression to composite expression.
     *
     * @param  mixed $part
     */
    public function add($part): self
    {
        if (!empty($part) || ($part instanceof self && $part->count() > 0)) {
            $this->parts[] = $part;
        }

        return $this;
    }

    /**
     * Adds multiple parts to composite expression.
     *
     * @param  array $parts
     */
    public function addMultiple(array $parts = []): self
    {
        foreach ((array) $parts as $part) {
            $this->add($part);
        }

        return $this;
    }

    /**
     * Retrieves the amount of expressions on composite expression.
     */
    public function count(): int
    {
        return count($this->parts);
    }

    /**
     * Retrieves the string representation of this composite expression.
     *
     * @return string
     */
    public function __toString()
    {
        if (count($this->parts) === 1) {
            return (string) $this->parts[0];
        }

        return '(' . implode(') ' . $this->type . ' (', $this->parts) . ')';
    }
}
