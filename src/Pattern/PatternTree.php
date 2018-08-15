<?php

namespace AnyMark\Pattern;

interface PatternTree
{
    /**
     * Get the subpatterns of a certain pattern. If no parent
     * is specified the patterns that process at root are returned.
     */
    public function getSubpatterns(Pattern $parentPattern = null) : array;
}
