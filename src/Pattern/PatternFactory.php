<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Pattern;

/**
 * @package AnyMark
 */
interface PatternFactory
{
	/**
	 * @param string $patternClass
	 * @return \AnyMark\Pattern\Pattern
	 */
	public function create($patternClass);
}