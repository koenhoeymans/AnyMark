<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Pattern;

/**
 * @package AnyMark
 */
interface PatternTree
{
    /**
     * Get the subpatterns of a certain pattern. If no parent
     * is specified the patterns that process at root are returned.
     *
     * @param  Pattern $pattern
     * @return array   An array with the subpatterns.
     */
    public function getSubpatterns(Pattern $parentPattern = null);
}
