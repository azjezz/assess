<?php

declare(strict_types=1);

namespace Assess\Event;

/**
 * Represents the types of events that can occur to a node.
 */
enum EventType: int
{
    /**
     * Indicates that a node has been created.
     */
    case Created = 1;

    /**
     * Indicates that a node has been accessed.
     */
    case Accessed = 2;

    /**
     * Indicates that a node has been modified.
     */
    case Modified = 3;

    /**
     * Indicates that a node's metadata has changed.
     */
    case Changed = 4;

    /**
     * Indicates that a node has been moved.
     */
    case Moved = 5;

    /**
     * Indicates that a node has been deleted.
     */
    case Deleted = 6;
}
