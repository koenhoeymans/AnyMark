<?php

/**
 * @package AnyMark
 */
namespace AnyMark\PublicApi;

/**
 * Add a pattern to process at a certain position. This is the last part of a fluent
 * interface that starts with `\AnyMark\PublicApi\EditPatternConfig`.
 * @package AnyMark
 */
interface Where
{
    /**
     * Add as last to process. Other patterns may be added as last afterwards.
     *
     * @return void
     */
    public function last();

    /**
     * Add as first to process. Other patterns may be added as first afterwards.
     *
     * @return void
     */
    public function first();

    /**
     * Add to process after a certain pattern. Other patterns may be added
     * after that pattern afterwards.
     *
     * @param  string $patternName
     * @return void
     */
    public function after($patternName);

    /**
     * Add to process before a certain pattern. Other patterns may be added
     * before that pattern afterwards.
     *
     * @param  string $patternName
     * @return void
     */
    public function before($patternName);
}
