<?php

declare(strict_types=1);

namespace Assess\Indexer;

use Assess\Node;

final readonly class Index
{
    /**
     * The indexed nodes.
     *
     * @var array<string, Node\Node>
     */
    public array $nodes;

    /**
     * Creates a new instance of the index.
     *
     * @param array<string, Node\Node> $nodes The indexed nodes.
     */
    public function __construct(array $nodes)
    {
        $this->nodes = $nodes;
    }

    /**
     * Adds the given node to the index.
     *
     * @param Node\Node $node The node to add to the index.
     *
     * @return Index The updated index.
     */
    public function withNode(Node\Node $node): self
    {
        $nodes = $this->nodes;
        $nodes[$node->id] = $node;

        return new self($nodes);
    }

    /**
     * Merges the given index with this index.
     *
     * @param Index $other The index to merge with this index.
     *
     * @return Index The merged index.
     */
    public function merge(self $other): self
    {
        $index = clone $this;
        foreach ($other->nodes as $node) {
            $index = $index->withNode($node);
        }

        return $index;
    }
}