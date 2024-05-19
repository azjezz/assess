<?php

declare(strict_types=1);

namespace Assess\Comparison;

use Assess\Configuration;
use Assess\Indexer\Index;

final readonly class Comparator implements ComparatorInterface
{
    /**
     * @inheritDoc
     */
    public function compare(Index $index, Index $other, Configuration $configuration): Result
    {
        $created = [];
        $modified = [];
        $accessed = [];
        $changed = [];
        $moved = [];
        $deleted = [];

        // Check for created nodes
        foreach ($other->nodes as $id => $node) {
            if (!isset($index->nodes[$id])) {
                $created[] = $id;
            }
        }

        // Check for modified, accessed, changed, and moved nodes
        foreach ($index->nodes as $id => $node) {
            if (isset($other->nodes[$id])) {
                $otherNode = $other->nodes[$id];

                if ($configuration->watchForAccess) {
                    // Check last access time
                    if ($node->statistics->lastAccessTime !== $otherNode->statistics->lastAccessTime) {
                        $accessed[] = $id;
                    }
                }

                if ($configuration->watchForModifications) {
                    // Check last modified time
                    if ($node->statistics->lastModifiedTime !== $otherNode->statistics->lastModifiedTime) {
                        $modified[] = $id;
                    }
                }

                if ($configuration->watchForChanges) {
                    // Check for other metadata changes (ctime)
                    if ($node->statistics->lastChangeTime !== $otherNode->statistics->lastChangeTime) {
                        $changed[] = $id;
                    }
                }

                // Check for moved files (path change with same id)
                if ($node->path !== $otherNode->path) {
                    $moved[] = $id;
                }
            }
        }

        // Check for deleted nodes
        foreach ($index->nodes as $id => $node) {
            if (!isset($other->nodes[$id])) {
                $deleted[] = $id;
            }
        }

        return new Result(
            created: $created,
            modified: $modified,
            accessed: $accessed,
            changed: $changed,
            moved: $moved,
            deleted: $deleted,
        );
    }
}
