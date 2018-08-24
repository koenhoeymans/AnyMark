<?php

namespace AnyMark\Api;

/**
 * This event is thrown after the configuration of the patterns is loaded. It
 * allows for the modification of the configuration of the pattern order and
 * the patterns that are used.
 */
interface PatternConfigLoaded
{
    /**
     * Set the implementation that should be used for a given pattern name. This can
     * be a class name or an object.
     *
     * @param string|object $implementation The class name or object.
     */
    public function setImplementation(string $name, $implementation): void;

    /**
     * Adds a pattern to the configuration. It can be added to an alias
     * or as a subpattern, choosing where to place it.
     *
     * This method uses a fluent interface and is followed by `ToAliasOrParent`
     *
     * Example:
     *
     *     $configuration
     *         ->add('strong')
     *         ->toAlias('inline')
     *         ->last();
     *
     *    $configuration
     *        ->add('strong')
     *        ->toParent('italic')
     *        ->first();
     */
    public function add(string $name): ToAliasOrParent;
}
