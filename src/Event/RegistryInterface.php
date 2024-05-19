<?php

declare(strict_types=1);

namespace Assess\Event;

use Closure;

/**
 * An event registrar.
 */
interface RegistryInterface
{
    /**
     * Registers a listener to the event.
     *
     * @param EventType $event The event to listen for.
     *
     * @param (Closure(Event): void) $listener The listener to attach.
     */
    public function register(EventType $event, Closure $listener): void;
}
