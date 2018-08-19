<?php

namespace AnyMark\Api;

/**
 * This event is thrown one time, before the first time the parser
 * is put into action. It allows to change the configuration file
 * with the pattern configuration. An example configuration file
 * could contain the following:
 *
 *     return [
 *         "implementations" => [
 *             "emphasis" => "\\AnyMark\\Pattern\\Patterns\\Emphasis",
 "header" => "\\AnyMark\\Pattern\\Patterns\\Header",
 *         ],
 *         "alias" => [
 *             "foo" => ["emphasis", "header"]
 *         ],
 *         "tree" => [
 *             "foo" => ["emphasis"],
 *             "header" => ["header"]
 *         ]
 *     ];
 *
 * The default configuration file can be found in the `AnyMark` directory as
 * `Patterns.php`.
 */
interface SetPatternConfig
{
    /**
     * Sets the file where the pattern configuration is found.
     */
    public function setPatternConfigFile(string $file);
}
