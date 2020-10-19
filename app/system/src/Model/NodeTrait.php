<?php

namespace Pagekit\System\Model;

trait NodeTrait
{
    protected ?NodeInterface $parent = null;

    /**
     * @var NodeInterface[]
     */
    protected array $children = [];

    /**
     * @return NodeInterface|null
     */
    public function getParent(): ?NodeInterface
    {
        return $this->parent;
    }

    /**
     * {@inheritdoc}
     */
    public function setParent(NodeInterface $parent = null): NodeInterface
    {
        if ($parent === $this) {
            throw new \InvalidArgumentException('A node cannot have itself as a parent');
        }

        if ($parent === $this->parent) {
            return $this;
        }

        if ($this->parent !== null) {
            $this->parent->remove($this);
        }

        $this->parent = $parent;

        if ($this->parent !== null && !$this->parent->contains($this, false)) {
            $this->parent->add($this);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function hasChildren(): bool
    {
        return !empty($this->children);
    }

    /**
     * {@inheritdoc}
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    /**
     * {@inheritdoc}
     */
    public function add(NodeInterface $node): NodeInterface
    {
         $this->children[$node->hashCode()] = $node->setParent($this);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addAll(array $nodes): NodeInterface
    {
        foreach ($nodes as $node) {
            $this->add($node);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function remove($node): bool
    {
        $hash = $node instanceof NodeInterface ? $node->hashCode() : (string) $node;

        if ($node = $this->findChild($hash)) {

            unset($this->children[$hash]);
            $node->setParent(null);

            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function removeAll(array $nodes = []): bool
    {
        if (empty($nodes)) {

            foreach ($this->children as $child) {
                $child->setParent(null);
            }

            $this->children = [];

            return true;
        }

        $bool = false;

        foreach ($nodes as $node) {
            if ($this->remove($node)) {
                $bool = true;
            }
        }

        return $bool;
    }

    /**
     * {@inheritdoc}
     */
    public function findChild($hash, $recursive = true): ?NodeInterface
    {
        $node = isset($this->children[$hash]) ? $this->children[$hash] : null;

        if (!$node && $recursive) {
            foreach($this->getChildren() as $n) {
                if ($child = $n->findChild($hash, $recursive)) {
                    return $child;
                }
            }
        }

        return $node;
    }

    /**
     * {@inheritdoc}
     */
    public function contains($node, $recursive = true): bool
    {
        return $this->findChild(($node instanceof NodeInterface ? $node->hashCode() : (string) $node), $recursive) !== null;
    }

    /**
     * {@inheritdoc}
     */
    public function getDepth(): int
    {
        if ($this->parent === null) {
            return 0;
        }

        return $this->parent->getDepth() + 1;
    }

    /**
     * {@inheritdoc}
     */
    public function hashCode(): string
    {
        return spl_object_hash($this);
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->children);
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->children);
    }
}
