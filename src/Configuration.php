<?php

declare(strict_types=1);

namespace Assess;

/**
 * Configuration class for the filesystem watcher.
 *
 * This class holds configuration settings for the filesystem watcher, including polling intervals, directories to watch,
 * file extensions to include/exclude, and whether to watch directories.
 */
final readonly class Configuration
{
    /**
     * Default poll interval.
     */
    public const float DEFAULT_POLL_INTERVAL = 0.2;

    /**
     * Default watch directories.
     */
    public const bool DEFAULT_WATCH_DIRECTORIES = true;

    /**
     * Default watch for access.
     */
    public const bool DEFAULT_WATCH_FOR_ACCESS = false;

    /**
     * Default watch for modifications.
     */
    public const bool DEFAULT_WATCH_FOR_MODIFICATIONS = true;

    /**
     * Default watch for changes.
     */
    public const bool DEFAULT_WATCH_FOR_CHANGES = false;

    /**
     * Poll interval in seconds.
     */
    public float $pollInterval;

    /**
     * List of directories to watch.
     *
     * @var list<non-empty-string>
     */
    public array $directories;

    /**
     * List of file extensions to watch.
     *
     * @var list<non-empty-string>
     */
    public array $extensions;

    /**
     * List of directories to exclude from watching.
     *
     * @var list<non-empty-string>
     */
    public array $excludedDirectories;

    /**
     * List of file extensions to exclude from watching.
     *
     * @var list<non-empty-string>
     */
    public array $excludedExtensions;

    /**
     * List of specific files to exclude from watching.
     *
     * @var list<non-empty-string>
     */
    public array $excludedFiles;

    /**
     * Whether to watch directories or only files.
     */
    public bool $watchDirectories;

    /**
     * Whether to watch for file modifications.
     */
    public bool $watchForModifications;

    /**
     * Whether to watch for file changes.
     */
    public bool $watchForChanges;

    /**
     * Whether to watch for file access.
     */
    public bool $watchForAccess;

    /**
     * Constructor for Configuration.
     *
     * @param float $pollInterval Poll interval in seconds.
     * @param list<non-empty-string> $directories List of directories to watch.
     * @param list<non-empty-string> $extensions List of file extensions to watch.
     * @param list<non-empty-string> $excludedDirectories List of directories to exclude from watching.
     * @param list<non-empty-string> $excludedExtensions List of file extensions to exclude from watching.
     * @param list<non-empty-string> $excludedFiles List of specific files to exclude from watching.
     * @param bool $watchDirectories Whether to watch directories or only files.
     * @param bool $watchForAccess Whether to watch for file access.
     * @param bool $watchForModifications Whether to watch for file modifications.
     * @param bool $watchForChanges Whether to watch for file changes.
     */
    public function __construct(
        float $pollInterval = self::DEFAULT_POLL_INTERVAL,
        array $directories = [],
        array $extensions = [],
        array $excludedDirectories = [],
        array $excludedExtensions = [],
        array $excludedFiles = [],
        bool $watchDirectories = self::DEFAULT_WATCH_DIRECTORIES,
        bool $watchForAccess = self::DEFAULT_WATCH_FOR_ACCESS,
        bool $watchForModifications = self::DEFAULT_WATCH_FOR_MODIFICATIONS,
        bool $watchForChanges = self::DEFAULT_WATCH_FOR_CHANGES,
    ) {
        $this->pollInterval = $pollInterval;
        $this->directories = $directories;
        $this->extensions = $extensions;
        $this->excludedDirectories = $excludedDirectories;
        $this->excludedExtensions = $excludedExtensions;
        $this->excludedFiles = $excludedFiles;
        $this->watchDirectories = $watchDirectories;
        $this->watchForAccess = $watchForAccess;
        $this->watchForModifications = $watchForModifications;
        $this->watchForChanges = $watchForChanges;
    }

    /**
     * Create a new instance of Configuration with default settings for the given directories.
     *
     * @param list<non-empty-string> $directories List of directories to watch.
     *
     * @return self New instance with default settings.
     */
    public static function createForDirectories(array $directories): self
    {
        return new self(
            self::DEFAULT_POLL_INTERVAL,
            $directories,
            [],
            [],
            [],
            [],
            true,
            true,
            true,
            true
        );
    }

    /**
     * Set the poll interval.
     *
     * @param float $pollInterval Poll interval in seconds.
     *
     * @return self New instance with updated poll interval.
     */
    public function withPollInterval(float $pollInterval): self
    {
        return new self(
            $pollInterval,
            $this->directories,
            $this->extensions,
            $this->excludedDirectories,
            $this->excludedExtensions,
            $this->excludedFiles,
            $this->watchDirectories,
            $this->watchForModifications,
            $this->watchForChanges,
            $this->watchForAccess
        );
    }

    /**
     * Set the directories to watch.
     *
     * @param list<non-empty-string> $directories List of directories to watch.
     *
     * @return self New instance with updated directories.
     */
    public function withDirectories(array $directories): self
    {
        return new self(
            $this->pollInterval,
            $directories,
            $this->extensions,
            $this->excludedDirectories,
            $this->excludedExtensions,
            $this->excludedFiles,
            $this->watchDirectories,
            $this->watchForModifications,
            $this->watchForChanges,
            $this->watchForAccess
        );
    }

    /**
     * Set the file extensions to watch.
     *
     * @param list<non-empty-string> $extensions List of file extensions to watch.
     *
     * @return self New instance with updated extensions.
     */
    public function withExtensions(array $extensions): self
    {
        return new self(
            $this->pollInterval,
            $this->directories,
            $extensions,
            $this->excludedDirectories,
            $this->excludedExtensions,
            $this->excludedFiles,
            $this->watchDirectories,
            $this->watchForModifications,
            $this->watchForChanges,
            $this->watchForAccess
        );
    }

    /**
     * Set the directories to exclude from watching.
     *
     * @param list<non-empty-string> $excludedDirectories List of directories to exclude from watching.
     *
     * @return self New instance with updated excluded directories.
     */
    public function withExcludedDirectories(array $excludedDirectories): self
    {
        return new self(
            $this->pollInterval,
            $this->directories,
            $this->extensions,
            $excludedDirectories,
            $this->excludedExtensions,
            $this->excludedFiles,
            $this->watchDirectories,
            $this->watchForModifications,
            $this->watchForChanges,
            $this->watchForAccess
        );
    }

    /**
     * Set the file extensions to exclude from watching.
     *
     * @param list<non-empty-string> $excludedExtensions List of file extensions to exclude from watching.
     *
     * @return self New instance with updated excluded extensions.
     */
    public function withExcludedExtensions(array $excludedExtensions): self
    {
        return new self(
            $this->pollInterval,
            $this->directories,
            $this->extensions,
            $this->excludedDirectories,
            $excludedExtensions,
            $this->excludedFiles,
            $this->watchDirectories,
            $this->watchForModifications,
            $this->watchForChanges,
            $this->watchForAccess
        );
    }

    /**
     * Set the specific files to exclude from watching.
     *
     * @param list<non-empty-string> $excludedFiles List of specific files to exclude from watching.
     *
     * @return self New instance with updated excluded files.
     */
    public function withExcludedFiles(array $excludedFiles): self
    {
        return new self(
            $this->pollInterval,
            $this->directories,
            $this->extensions,
            $this->excludedDirectories,
            $this->excludedExtensions,
            $excludedFiles,
            $this->watchDirectories,
            $this->watchForModifications,
            $this->watchForChanges,
            $this->watchForAccess
        );
    }

    /**
     * Set whether to watch directories or only files.
     *
     * @param bool $watchDirectories Whether to watch directories or only files.
     *
     * @return self New instance with updated watch directories setting.
     */
    public function withWatchDirectories(bool $watchDirectories): self
    {
        return new self(
            $this->pollInterval,
            $this->directories,
            $this->extensions,
            $this->excludedDirectories,
            $this->excludedExtensions,
            $this->excludedFiles,
            $watchDirectories,
            $this->watchForModifications,
            $this->watchForChanges,
            $this->watchForAccess
        );
    }

    /**
     * Set whether to watch for file access.
     *
     * @param bool $watchForAccess Whether to watch for file access.
     *
     * @return self New instance with updated watch for access setting.
     */
    public function withWatchForAccess(bool $watchForAccess): self
    {
        return new self(
            $this->pollInterval,
            $this->directories,
            $this->extensions,
            $this->excludedDirectories,
            $this->excludedExtensions,
            $this->excludedFiles,
            $this->watchDirectories,
            $watchForAccess,
            $this->watchForModifications,
            $this->watchForChanges,
        );
    }

    /**
     * Set whether to watch for file modifications.
     *
     * @param bool $watchForModifications Whether to watch for file modifications.
     *
     * @return self New instance with updated watch for modifications setting.
     */
    public function withWatchForModifications(bool $watchForModifications): self
    {
        return new self(
            $this->pollInterval,
            $this->directories,
            $this->extensions,
            $this->excludedDirectories,
            $this->excludedExtensions,
            $this->excludedFiles,
            $this->watchDirectories,
            $this->watchForAccess,
            $watchForModifications,
            $this->watchForChanges,
        );
    }

    /**
     * Set whether to watch for file changes.
     *
     * @param bool $watchForChanges Whether to watch for file changes.
     *
     * @return self New instance with updated watch for changes setting.
     */
    public function withWatchForChanges(bool $watchForChanges): self
    {
        return new self(
            $this->pollInterval,
            $this->directories,
            $this->extensions,
            $this->excludedDirectories,
            $this->excludedExtensions,
            $this->excludedFiles,
            $this->watchDirectories,
            $this->watchForAccess,
            $this->watchForModifications,
            $watchForChanges,
        );
    }
}
