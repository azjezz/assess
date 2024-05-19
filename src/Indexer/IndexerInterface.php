<?php

declare(strict_types=1);

namespace Assess\Indexer;

use Assess\Configuration;

/**
 * Defines the contract for an indexer that creates an index of files based on a given configuration.
 */
interface IndexerInterface
{
    /**
     * Creates an index of files based on the given configuration.
     *
     * @param Configuration $configuration The configuration for indexing files.
     *
     * @return Index The resulting index of files.
     */
    public function index(Configuration $configuration): Index;
}
