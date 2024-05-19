<?php

declare(strict_types=1);

namespace Assess\Event;

use Assess\Comparison\Result;
use Assess\Configuration;
use Assess\Indexer\Index;

final class Producer implements ProducerInterface
{
    /**
     * @inheritDoc
     */
    public function produce(Result $result, Index $oldIndex, Index $newIndex, Configuration $configuration): iterable
    {
        foreach ($result->created as $id) {
            yield new Event(EventType::Created, $id, $oldIndex, $newIndex);
        }

        if ($configuration->watchForAccess) {
            foreach ($result->accessed as $id) {
                yield new Event(EventType::Accessed, $id, $oldIndex, $newIndex);
            }
        }

        if ($configuration->watchForModifications) {
            foreach ($result->modified as $id) {
                yield new Event(EventType::Modified, $id, $oldIndex, $newIndex);
            }
        }

        if ($configuration->watchForChanges) {
            foreach ($result->changed as $id) {
                yield new Event(EventType::Changed, $id, $oldIndex, $newIndex);
            }
        }

        foreach ($result->moved as $id) {
            yield new Event(EventType::Moved, $id, $oldIndex, $newIndex);
        }

        foreach ($result->deleted as $id) {
            yield new Event(EventType::Deleted, $id, $oldIndex, $newIndex);
        }
    }
}