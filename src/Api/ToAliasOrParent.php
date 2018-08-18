<?php

namespace AnyMark\Api;

/**
 * This is part of a fluent api that started with `\AnyMark\Api\EditPatternConfig`.
 * A pattern can be added as part of an alias (eg. block, inline) or as
 * subpattern.
 */
interface ToAliasOrParent
{
    /**
     * Adds the pattern to an alias.
     */
    public function toAlias(string $name) : Where;

    /**
     * Adds the pattern as a subpattern of another (parent)pattern.
     */
    public function toParent(string $name) : Where;
}
