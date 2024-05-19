<?php

declare(strict_types=1);

namespace Assess\Node;

use JsonSerializable;

/**
 * Represents a node with a unique identifier, path, and statistics.
 *
 * @psalm-import-type Stats from Statistics
 */
final readonly class Node implements JsonSerializable
{
    /**
     * Unique identifier for the node.
     *
     * @var non-empty-string
     */
    public string $id;

    /**
     * The path to the node.
     *
     * @var non-empty-string
     */
    public string $path;

    /**
     * The statistics of the file.
     */
    public Statistics $statistics;

    /**
     * Creates a new instance of the node.
     *
     * @param non-empty-string $id Unique identifier for the node.
     * @param non-empty-string $path The path to the node.
     * @param Statistics $statistics The statistics of the node.
     */
    public function __construct(string $id, string $path, Statistics $statistics)
    {
        $this->id = $id;
        $this->path = $path;
        $this->statistics = $statistics;
    }

    /**
     * Creates a new instance of the node from the given path and statistics.
     *
     * @param non-empty-string $path The path to the node.
     * @param Statistics $statistics The statistics of the node.
     *
     * @return Node The new instance of the node.
     */
    public static function createFromPathAndStatistics(string $path, Statistics $statistics): self
    {
        return new self($statistics->getIdentifier(), $path, $statistics);
    }

    /**
     * Return an array representation of the node.
     *
     * @return array{
     *     id: non-empty-string,
     *     path: non-empty-string,
     *     statistics: Stats,
     * }
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'path' => $this->path,
            'statistics' => $this->statistics->jsonSerialize()
        ];
    }
}
