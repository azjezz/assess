<?php

declare(strict_types=1);

namespace Assess\Node;

use JsonSerializable;

/**
 * Represents the statistics of a node.
 *
 * @psalm-type Stats = array{
 *      dev: int,
 *      ino: int,
 *      mode: int,
 *      nlink: int,
 *      uid: int,
 *      gid: int,
 *      rdev: int,
 *      size: int,
 *      atime: int,
 *      mtime: int,
 *      ctime: int,
 *      blksize: int,
 *      blocks: int,
 * }
 */
final readonly class Statistics implements JsonSerializable
{
    /**
     * Device ID
     */
    public int $deviceId;

    /**
     * Inode number
     */
    public int $inode;

    /**
     * Mode
     */
    public int $mode;

    /**
     * Number of links
     */
    public int $numberOfLinks;

    /**
     * User ID of the owner
     */
    public int $userId;

    /**
     * Group ID of the owner
     */
    public int $groupId;

    /**
     * Device type (if inode device)
     */
    public int $deviceType;

    /**
     * Total size, in bytes
     */
    public int $size;

    /**
     * Last access time
     */
    public int $lastAccessTime;

    /**
     * Last modification time
     */
    public int $lastModifiedTime;

    /**
     * Last status change time
     */
    public int $lastChangeTime;

    /**
     * Block size for filesystem I/O
     */
    public int $blockSize;

    /**
     * Number of 512B blocks allocated
     */
    public int $numberOfBlocks;

    /**
     * Create a new node statistics instance.
     *
     * @param int $deviceId Device ID
     * @param int $inode Inode number
     * @param int $mode File mode
     * @param int $numberOfLinks Number of links
     * @param int $userId User ID of the owner
     * @param int $groupId Group ID of the owner
     * @param int $deviceType Device type (if inode device)
     * @param int $size Total size, in bytes
     * @param int $lastAccessTime Last access time
     * @param int $lastModifiedTime Last modification time
     * @param int $lastChangeTime Last status change time
     * @param int $blockSize Block size for filesystem I/O
     * @param int $numberOfBlocks Number of 512B blocks allocated
     */
    public function __construct(
        int $deviceId,
        int $inode,
        int $mode,
        int $numberOfLinks,
        int $userId,
        int $groupId,
        int $deviceType,
        int $size,
        int $lastAccessTime,
        int $lastModifiedTime,
        int $lastChangeTime,
        int $blockSize,
        int $numberOfBlocks
    ) {
        $this->deviceId = $deviceId;
        $this->inode = $inode;
        $this->mode = $mode;
        $this->numberOfLinks = $numberOfLinks;
        $this->userId = $userId;
        $this->groupId = $groupId;
        $this->deviceType = $deviceType;
        $this->size = $size;
        $this->lastAccessTime = $lastAccessTime;
        $this->lastModifiedTime = $lastModifiedTime;
        $this->lastChangeTime = $lastChangeTime;
        $this->blockSize = $blockSize;
        $this->numberOfBlocks = $numberOfBlocks;
    }

    /**
     * Create a {@see Statistics} instance from an array of stats.
     *
     * @param Stats $stats Array of file statistics.
     *
     * @return self
     */
    public static function createFromArray(array $stats): self
    {
        return new self(
            $stats['dev'],
            $stats['ino'],
            $stats['mode'],
            $stats['nlink'],
            $stats['uid'],
            $stats['gid'],
            $stats['rdev'],
            $stats['size'],
            $stats['atime'],
            $stats['mtime'],
            $stats['ctime'],
            $stats['blksize'],
            $stats['blocks']
        );
    }

    /**
     * Get a unique identifier for the node statistics.
     *
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->inode . '-' . $this->deviceId;
    }

    /**
     * Convert the {@see Statistics} instance to an array.
     *
     * @return Stats
     */
    public function toArray(): array
    {
        return [
            'dev' => $this->deviceId,
            'ino' => $this->inode,
            'mode' => $this->mode,
            'nlink' => $this->numberOfLinks,
            'uid' => $this->userId,
            'gid' => $this->groupId,
            'rdev' => $this->deviceType,
            'size' => $this->size,
            'atime' => $this->lastAccessTime,
            'mtime' => $this->lastModifiedTime,
            'ctime' => $this->lastChangeTime,
            'blksize' => $this->blockSize,
            'blocks' => $this->numberOfBlocks,
        ];
    }

    /**
     * Return an array representation of the node statistics.
     *
     * @return Stats
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
