<?php

/**
 * @package AnyMark
 */
namespace AnyMark\PublicApi;

/**
 * This is part of a fluent api that started with `\AnyMark\PublicApi\EditPatternConfig`.
 * A pattern can be added as part of an alias (eg. block, inline) or as @author koen
 * subpattern.
 *
 * @package AnyMark
 */
interface ToAliasOrParent
{
    /**
     * Adds the pattern to an alias.
     *
     * @param  string                   $name
     * @return \AnyMark\PublicApi\Where
     */
    public function toAlias($name);

    /**
     * Adds the pattern as a subpattern of another (parent)pattern.
     *
     * @param  string                   $name
     * @return \AnyMark\PublicApi\Where
     */
    public function toParent($name);
}
