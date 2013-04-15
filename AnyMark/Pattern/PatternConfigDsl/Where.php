<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Pattern\PatternConfigDsl;

/**
 * Add a pattern to process at a certain position.
 * 
 * @package AnyMark
 */
interface Where
{
	/**
	 * Add as last to process. Other patterns may be added as last afterwards.
	 */
	public function last();

	/**
	 * Add as first to process. Other patterns may be added as first afterwards.
	 */
	public function first();

	/**
	 * Add to process after a certain pattern. Other patterns may be added
	 * after that pattern afterwards.
	 * 
	 * @param string $patternName
	 */
	public function after($patternName);

	/**
	 * Add to process before a certain pattern. Other patterns may be added
	 * before that pattern afterwards.
	 * 
	 * @param string $patternName
	 */
	public function before($patternName);
}