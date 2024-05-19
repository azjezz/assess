<?php

declare(strict_types=1);

namespace Assess\Event;

use Assess\Comparison\Result;
use Assess\Configuration;
use Assess\Indexer\Index;

/**
 * Produces events based on the comparison of two indexes.
 */
interface ProducerInterface
{
    /**
     * Produce events based on the comparison result.
     *
     * @param Result $result The comparison result.
     * @param Index $oldIndex The old index.
     * @param Index $newIndex The new index.
     * @param Configuration $configuration The configuration.
     *
     * @return iterable<Event> An iterable list of events.
     */
    public function produce(Result $result, Index $oldIndex, Index $newIndex, Configuration $configuration): iterable;
}
