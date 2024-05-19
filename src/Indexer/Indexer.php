<?php

declare(strict_types=1);

namespace Assess\Indexer;

use Amp\File\Filesystem;
use Assess\Configuration;
use Assess\Node\Statistics;
use Assess\Node\Node;

use function in_array;
use function pathinfo;
use function str_starts_with;
use function strtolower;

use const PATHINFO_EXTENSION;

final readonly class Indexer implements IndexerInterface
{
    /**
     * The filesystem to use for indexing files.
     *
     * @var Filesystem
     */
    private Filesystem $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * @inheritDoc
     */
    public function index(Configuration $configuration): Index
    {
        $directories = $configuration->directories;
        $index = new Index([]);

        foreach ($directories as $directory) {
            $index = $index->merge(
                $this->indexDirectory($directory, $configuration),
            );
        }

        return $index;
    }

    private function indexDirectory(string $directory, Configuration $configuration): Index
    {
        $index = new Index([]);
        if (!$this->filesystem->isDirectory($directory)) {
            return $index;
        }

        if ($configuration->watchDirectories) {
            $node = $this->getNode($directory, $configuration);
            if ($node === null) {
                return $index;
            }

            $index = $index->withNode($node);
        }

        foreach ($this->filesystem->listFiles($directory) as $file) {
            $path = $directory . '/' . $file;
            if ($this->filesystem->isDirectory($path)) {
                $index = $index->merge($this->indexDirectory($path, $configuration));

                if (!$configuration->watchDirectories) {
                    continue;
                }
            }

            $node = $this->getNode($path, $configuration);
            if ($node === null) {
                continue;
            }

            $index = $index->withNode($node);
        }

        return $index;
    }

    private function getNode(string $path, Configuration $configuration): ?Node
    {
        if (!$this->filesystem->exists($path)) {
            return null;
        }

        $extension = pathinfo($path, PATHINFO_EXTENSION);
        $extension = strtolower($extension);
        if ([] !== $configuration->extensions) {
            // If the file extension is not allowed:
            if (!in_array($extension, $configuration->extensions, true)) {
                return null;
            }
        }

        // If the file extension is excluded:
        if (in_array($extension, $configuration->excludedExtensions, true)) {
            return null;
        }

        foreach ($configuration->excludedDirectories as $excludedDirectory) {
            if (str_starts_with($path, $excludedDirectory)) {
                return null;
            }
        }

        $status = $this->filesystem->getStatus($path);
        if  ($status === null) {
            return null;
        }

        $statistics = Statistics::createFromArray($status);

        return Node::createFromPathAndStatistics($path, $statistics);
    }
}