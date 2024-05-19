<?php

declare(strict_types=1);

namespace Assess\Examples;

use Assess\Configuration;
use Assess\Event\Event;
use Assess\Event\EventType;
use Assess\Watcher;
use Revolt\EventLoop;

use function array_slice;
use function array_map;
use function array_unique;
use function memory_get_peak_usage;

use const SIGINT;

require_once __DIR__ . '/../vendor/autoload.php';

$directories = array_slice($argv, 1);
$directories = array_map(realpath(...), $directories);
$directories = array_unique($directories);

if (empty($directories)) {
    echo "Usage: php command.php <directory> [<directory> ...]\n";
    exit(1);
}

$configuration = Configuration::createForDirectories($directories)
    // poll interval in seconds
    ->withPollInterval(0.5)
    // do not watch directories
    ->withWatchDirectories(false)
    // include only PHP files
    ->withExtensions(['php'])
    // do not watch for access
    ->withWatchForAccess(true)
    // do not watch for changes
    ->withWatchForChanges(true)
;

$watcher = Watcher::create($configuration);

$watcher->register(EventType::Created, function (Event $event): void {
    $node = $event->newIndex->nodes[$event->id];

    echo "Created: {$node->path}\n";
});

$watcher->register(EventType::Modified, function (Event $event): void {
    $node = $event->newIndex->nodes[$event->id];

    echo "Modified: {$node->path}\n";
});

$watcher->register(EventType::Accessed, function (Event $event): void {
    $node = $event->newIndex->nodes[$event->id];

    echo "Accessed: {$node->path}\n";
});

$watcher->register(EventType::Changed, function (Event $event): void {
    $node = $event->newIndex->nodes[$event->id];

    echo "Changed: {$node->path}\n";
});

$watcher->register(EventType::Moved, function (Event $event): void {
    $oldNode = $event->oldIndex->nodes[$event->id];
    $newNode = $event->newIndex->nodes[$event->id];

    echo "Moved: {$oldNode->path} -> {$newNode->path}\n";
});

$watcher->register(EventType::Deleted, function (Event $event): void {
    $node = $event->oldIndex->nodes[$event->id];

    echo "Deleted: {$node->path}\n";
});

$watcher->enable();
$watcher->reference();

echo "Started watching...\n";

EventLoop::unreference(EventLoop::repeat($configuration->pollInterval * 5, function (): void {
    $peakMemory = memory_get_peak_usage(true) / 1024 / 1024;

    echo "Watching... (Peak memory: {$peakMemory} MB)\n";
}));

EventLoop::onSignal(SIGINT, function (string $callbackId) use($watcher): void {
    EventLoop::cancel($callbackId);

    echo "Stopping...\n";

    $watcher->disable();
    $watcher->unreference();
});

EventLoop::run();

echo "Stopped watching...\n";
