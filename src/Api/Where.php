<?php

namespace AnyMark\Api;

/**
 * Add a pattern to process at a certain position. This is the last part of a fluent
 * interface that starts with `\AnyMark\Api\EditPatternConfig`.
 */
interface Where
{
    /**
     * Add as last to process. Other patterns may be added as last afterwards.
     */
    public function last(): void;

    /**
     * Add as first to process. Other patterns may be added as first afterwards.
     */
    public function first(): void;

    /**
     * Add to process after a certain pattern. Other patterns may be added
     * after that pattern afterwards.
     */
    public function after(string $patternName): void;

    /**
     * Add to process before a certain pattern. Other patterns may be added
     * before that pattern afterwards.
     */
    public function before(string $patternName): void;
}
