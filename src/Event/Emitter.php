<?php

declare(strict_types=1);

namespace Assess\Event;

use Amp;
use Closure;

final class Emitter implements EmitterInterface, RegistryInterface
{
    /**
     * The listeners attached to the event emitter.
     *
     * @var array<int, list<(Closure(Event): void)>>
     */
    private array $listeners = [];

    /**
     * @inheritDoc
     */
    public function register(EventType $event, Closure $listener): void
    {
        $this->listeners[$event->value][] = $listener;
    }

    /**
     * @inheritDoc
     */
    public function emit(Event $event): void
    {
        if (!isset($this->listeners[$event->type->value])) {
            return;
        }

        $futures = [];
        foreach ($this->listeners[$event->type->value] as $listener) {
            $futures[] = Amp\async(static fn() => $listener($event));
        }

        Amp\Future\awaitAll($futures);
    }
}