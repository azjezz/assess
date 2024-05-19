<?php

declare(strict_types=1);

namespace Assess\Event;

/**
 * An event emitter.
 */
interface EmitterInterface
{
    /**
     * Emits an event.
     *
     * @param Event $event The event to emit.
     */
    public function emit(Event $event): void;
}