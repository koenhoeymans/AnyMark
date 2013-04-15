<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Pattern\PatternConfigDsl;

/**
 * @package AnyMark
 */
interface To
{
	/**
	 * @param string $parentPatternName
	 * @return Where
	 */
	public function to($parentPatternName);
}