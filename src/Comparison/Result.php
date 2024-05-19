<?php

declare(strict_types=1);

namespace Assess\Comparison;

final readonly class Result
{
    /**
     * The newly created nodes identifiers.
     *
     * @var list<non-empty-string>
     */
    public array $created;

    /**
     * The modified nodes identifiers.
     *
     * @var list<non-empty-string>
     */
    public array $modified;

    /**
     * The accessed nodes identifiers.
     *
     * @var list<non-empty-string>
     */
    public array $accessed;

    /**
     * The changed nodes identifiers.
     *
     * @var list<non-empty-string>
     */
    public array $changed;

    /**
     * The moved nodes identifiers.
     *
     * @var list<non-empty-string>
     */
    public array $moved;

    /**
     * The deleted nodes identifiers.
     *
     * @var list<non-empty-string>
     */
    public array $deleted;

    /**
     * Creates a new instance of the result.
     *
     * @param list<non-empty-string> $created The newly created nodes identifiers.
     * @param list<non-empty-string> $modified The modified nodes identifiers.
     * @param list<non-empty-string> $accessed The accessed nodes identifiers.
     * @param list<non-empty-string> $changed The changed nodes identifiers.
     * @param list<non-empty-string> $moved The moved nodes identifiers.
     * @param list<non-empty-string> $deleted The deleted nodes identifiers.
     */
    public function __construct(
        array $created,
        array $modified,
        array $accessed,
        array $changed,
        array $moved,
        array $deleted,
    ) {
        $this->created = $created;
        $this->modified = $modified;
        $this->accessed = $accessed;
        $this->changed = $changed;
        $this->moved = $moved;
        $this->deleted = $deleted;
    }

    /**
     * Determines if the result is empty.
     *
     * @return bool True if the result is empty, false otherwise.
     */
    public function isEmpty(): bool
    {
        return empty($this->created) &&
            empty($this->modified) &&
            empty($this->accessed) &&
            empty($this->changed) &&
            empty($this->moved) &&
            empty($this->deleted);
    }
}
