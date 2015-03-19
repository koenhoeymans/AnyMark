<?php

/**
 * @package AnyMark
 */
namespace AnyMark\PublicApi;

/**
 * This event is thrown one time, before the first time the parser
 * is put into action. It allows to change the configuration file
 * with the pattern configuration. An example configuration file
 * could contain the following:
 *
 *     return [
 *     		"implementations" => [
 *     			"emphasis" => "\\AnyMark\\Pattern\\Patterns\\Emphasis",
 "header" => "\\AnyMark\\Pattern\\Patterns\\Header",
 *     		],
 *     		"alias" => [
 *     			"foo" => ["emphasis", "header"]
 *      	],
 *     		"tree" => [
 *     			"foo" => ["emphasis"],
 *     			"header" => ["header"]
 *      	]
 *     ];
 *
 * The default configuration file can be found in the `AnyMark` directory as
 * `Patterns.php`.
 *
 * The eventname is `SetPatternConfigFileEvent`.
 *
 * @package AnyMark
 * @eventname SetPatternConfigFileEvent
 */
interface SetPatternConfigFileEvent
{
    /**
     * Sets the file where the pattern configuration is found.
     *
     * @param string $file The file with the pattern configuration.
     */
    public function setPatternConfigFile($file);
}
