<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Pattern\PatternConfigDsl;

use AnyMark\Pattern\Pattern;

/**
 * @package AnyMark
 */
interface Add
{
	/**
	 * Add a pattern to the configuration that determines what
	 * patterns are processing and what their relationships are.
	 * 
	 * @param string $patternName
	 * @param Pattern|string|null $implementation
	 * @return To
	 */
	public function add($name, $implementation = null);
}