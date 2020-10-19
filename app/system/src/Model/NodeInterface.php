<?php

namespace Pagekit\System\Model;

use Pagekit\System\Model\NodeInterface;

interface NodeInterface extends \IteratorAggregate, \Countable
{
    public function getParent(): ?self;

    /**
     * Sets the parent node.
     *
     * @param  NodeInterface|null $parent
     *
     * @throws \InvalidArgumentException
     */
    public function setParent(NodeInterface $parent = null): self;

    /**
     * Checks for child nodes.
     */
    public function hasChildren(): bool;

    /**
     * Gets all child nodes.
     *
     * @return NodeInterface[]
     */
    public function getChildren(): array;

    /**
     * Adds a node.
     *
     * @param  NodeInterface $node
     *
     * @throws \InvalidArgumentException
     */
    public function add(NodeInterface $node): self;

    /**
     * Add an array of nodes.
     *
     * @param  NodeInterface[]  $nodes
     */
    public function addAll(array $nodes): self;

    /**
     * Removes a node.
     *
     * @param  NodeInterface|string $node
     */
    public function remove($node): bool;

    /**
     * Removes all nodes or an given array of nodes.
     *
     * @param  (NodeInterface|string)[] $nodes
     */
    public function removeAll(array $nodes = []): bool;

    /**
     * Find a node by its hashcode.
     *
     * @param  string $hash
     * @param  bool   $recursive
     */
    public function findChild($hash, $recursive = true): ?self;


    /**
     * Checks if the tree contains the given node.
     *
     * @param  NodeInterface|string $node
     * @param  bool        $recursive
     */
    public function contains($node, $recursive = true): bool;

    /**
     * Gets the nodes depth.
     */
    public function getDepth(): int;

    /**
     * Returns a hashcode as unique identifier for a node.
     */
    public function hashCode(): string;
}
