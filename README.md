# Assess

Unix filesystem notifications library for PHP.

## Features

- Watch for file creations, modifications, accesses, changes, moves, and deletions.
- Configurable polling interval.
- Filter by file extensions.
- Optionally watch directories.
- Easy-to-use event registration.

## Installation

You can install the library via Composer:

```bash
composer require azjezz/assess
```

## Usage

```php
use Assess\Configuration;
use Assess\Event\Event;
use Assess\Event\EventType;
use Assess\Watcher;
use Revolt\EventLoop;

$configuration = Configuration::createForDirectories([
    '/path/to/directory',
    '/another/path/to/directory',
])
    // poll interval in seconds
    ->withPollInterval(0.5)
    // do not watch directories
    ->withWatchDirectories(false)
    // include only PHP files
    ->withExtensions(['php'])
;

$watcher = Watcher::create($configuration);

$watcher->register(EventType::Created, function (Event $event): void {
    $node = $event->newIndex->nodes[$event->id];

    echo "File created: {$node->path}\n";
});

$watcher->register(EventType::Moved, function (Event $event): void {
    $oldNode = $event->oldIndex->nodes[$event->id];
    $newNode = $event->newIndex->nodes[$event->id];

    echo "File moved: {$oldNode->path} -> {$newNode->path}\n";
});

$watcher->register(EventType::Deleted, function (Event $event): void {
    $node = $event->oldIndex->nodes[$event->id];

    echo "File deleted: {$node->path}\n";
});

$watcher->enable();
$watcher->reference();

EventLoop::run();
```

See [examples/command.php](examples/command.php) for a complete example.

## License

This library is licensed under the MIT license. See the [LICENSE](LICENSE) file for details.
