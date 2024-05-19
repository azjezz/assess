<?php

declare(strict_types=1);

namespace Assess\Comparison;

use Assess\Configuration;
use Assess\Indexer\Index;

/**
 * Defines the contract for a comparator that compares two file indexes and produces a result.
 */
interface ComparatorInterface
{
    /**
     * Compares two file indexes and produces a result.
     *
     * @param Index $index The original index.
     * @param Index $other The index to compare against.
     *
     * @return Result The result of the comparison, detailing the changes.
     */
    public function compare(Index $index, Index $other, Configuration $configuration): Result;
}
