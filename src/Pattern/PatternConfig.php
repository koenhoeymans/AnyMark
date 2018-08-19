<?php

namespace AnyMark\Pattern;

interface PatternConfig
{
    /**
     * Return a class name that should be used as the implementation for a pattern.
     *
     * @return object|string|null An object, class name, or null when not specified.
     */
    public function getSpecifiedImplementation(string $name);

    /**
     * The alias groups several patterns. Eg `inline` may be a name for several
     * patterns like `italic` and `strong`.
     */
    public function getAliased(string $alias): array;

    /**
     * Get the names of the subpatterns or aliases.
     */
    public function getSubnames(string $name): array;
}
