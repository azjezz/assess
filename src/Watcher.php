<?php

declare(strict_types=1);

namespace Assess;

use Amp\File\Filesystem;
use Assess\Comparison\Comparator;
use Assess\Comparison\ComparatorInterface;
use Assess\Event\Emitter;
use Assess\Event\EmitterInterface;
use Assess\Event\Producer;
use Assess\Event\ProducerInterface;
use Assess\Event\EventType;
use Assess\Event\RegistryInterface;
use Assess\Indexer\Index;
use Assess\Indexer\Indexer;
use Assess\Indexer\IndexerInterface;
use Closure;
use Revolt\EventLoop;
use WeakMap;

use function Amp\File\createDefaultDriver;

/**
 * Manages the file watching process, periodically indexing files, comparing indexes,
 * and emitting events based on changes.
 */
final class Watcher implements RegistryInterface
{
    private readonly Configuration $configuration;
    private readonly IndexerInterface $indexer;
    private readonly ComparatorInterface $comparator;
    private readonly ProducerInterface $producer;
    private readonly EmitterInterface&RegistryInterface $events;
    private string $eventLoopWatcherId;

    private ?Index $index = null;

    /**
     * Watcher constructor.
     *
     * @param Configuration $configuration The configuration settings.
     * @param IndexerInterface $indexer The indexer for creating file indexes.
     * @param ComparatorInterface $comparator The comparator for comparing file indexes.
     * @param ProducerInterface $producer The producer for creating events.
     * @param EmitterInterface&RegistryInterface $events The event emitter, and registry.
     */
    public function __construct(Configuration $configuration, IndexerInterface $indexer, ComparatorInterface $comparator, ProducerInterface $producer, EmitterInterface&RegistryInterface $events)
    {
        $this->configuration = $configuration;
        $this->indexer = $indexer;
        $this->comparator = $comparator;
        $this->producer = $producer;
        $this->events = $events;

        $this->eventLoopWatcherId = EventLoop::repeat($configuration->pollInterval, function(string  $eventLoopWatcherId): void {
            // disable the watcher while processing
            EventLoop::disable($eventLoopWatcherId);

            try {
                $oldIndex = $this->index;
                $newIndex = $this->indexer->index($this->configuration);

                if ($oldIndex === null) {
                    $this->index = $newIndex;

                    return;
                }

                $result = $this->comparator->compare($oldIndex, $newIndex, $this->configuration);
                $events = $this->producer->produce($result, $oldIndex, $newIndex, $this->configuration);
                foreach ($events as $event) {
                    $this->events->emit($event);
                }

                $this->index = $newIndex;
            } finally {
                // re-enable the watcher
                EventLoop::enable($eventLoopWatcherId);
            }
        });

        EventLoop::disable($this->eventLoopWatcherId);
    }

    /**
     * Creates a new {@see Watcher} instance with the given configuration.
     *
     * @param Configuration $configuration The configuration settings.
     *
     * @return self The new Watcher instance.
     */
    public static function create(Configuration $configuration): self
    {
        static $map;
        $map ??= new WeakMap();

        $loop = EventLoop::getDriver();
        if (isset($map[$loop])) {
            $filesystem = $map[$loop];
        } else {
            $filesystem = new Filesystem(createDefaultDriver());

            $map[$loop] = $filesystem;
        }

        return new self($configuration, new Indexer($filesystem), new Comparator(), new Producer(), new Emitter());
    }

    /**
     * @inheritDoc
     */
    public function register(EventType $event, Closure $listener): void
    {
        $this->events->register($event, $listener);
    }

    /**
     * Enables the file watcher.
     */
    public function enable(): void
    {
        EventLoop::enable($this->eventLoopWatcherId);
    }

    /**
     * Checks if the file watcher is enabled.
     *
     * @return bool True if the file watcher is enabled, false otherwise.
     */
    public function isEnabled(): bool
    {
        return EventLoop::isEnabled($this->eventLoopWatcherId);
    }

    /**
     * Disables the file watcher.
     */
    public function disable(): void
    {
        EventLoop::disable($this->eventLoopWatcherId);
    }

    /**
     * References the file watcher in the event loop.
     */
    public function reference(): void
    {
        EventLoop::reference($this->eventLoopWatcherId);
    }

    /**
     * Checks if the file watcher is referenced in the event loop.
     *
     * @return bool True if the file watcher is referenced, false otherwise.
     */
    public function isReferenced(): bool
    {
        return EventLoop::isReferenced($this->eventLoopWatcherId);
    }

    /**
     * Unreferences the file watcher from the event loop.
     */
    public function unreference(): void
    {
        EventLoop::unreference($this->eventLoopWatcherId);
    }

    /**
     * Destructor to clean up the event loop watcher.
     */
    public function __destruct()
    {
        EventLoop::cancel($this->eventLoopWatcherId);
    }
}