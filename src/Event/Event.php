<?php

declare(strict_types=1);

namespace Assess\Event;

use Assess\Indexer\Index;

/**
 * Represents an event that occurs to a node between two indexes.
 */
final readonly class Event
{
    /**
     * The type of the event.
     *
     * @var EventType
     */
    public EventType $type;

    /**
     * The unique identifier of the node that was affected.
     *
     * @var string
     */
    public string $id;

    /**
     * The old index from which the node can be retrieved before the event.
     *
     * @var Index
     */
    public Index $oldIndex;

    /**
     * The new index from which the node can be retrieved after the event.
     *
     * @var Index
     */
    public Index $newIndex;

    /**
     * Creates a new instance of the node event.
     *
     * @param string $id The unique identifier of the node that was affected.
     * @param Index $oldIndex The old index from which the node can be retrieved before the event.
     * @param Index $newIndex The new index from which the node can be retrieved after the event.
     */
    public function __construct(EventType $type, string $id, Index $oldIndex, Index $newIndex)
    {
        $this->type = $type;
        $this->id = $id;
        $this->oldIndex = $oldIndex;
        $this->newIndex = $newIndex;
    }
}
