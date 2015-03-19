<?php

/**
 * @package Anymark
 */
namespace AnyMark\Pattern;

/**
 * @package AnyMark
 */
interface PatternConfig
{
	/**
	 * Return a class name that should be used as the implementation for a pattern.
	 * 
	 * @param string $name
	 * @return object|string|null An object, class name, or null when not specified.
	 */
	public function getSpecifiedImplementation($name);

	/**
	 * The alias groups several patterns. Eg `inline` may be a name for several
	 * patterns like `italic` and `strong`.
	 * 
	 * @param string $alias
	 * @return array
	 */
	public function getAliased($alias);

	/**
	 * Get the names of the subpatterns or aliases.
	 * 
	 * @param string $name
	 * @return array
	 */
	public function getSubnames($name);
}