<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Pattern;

use Fjor\Fjor;

/**
 * @package AnyMark
 */
class FjorPatternFactory implements PatternFactory
{
	private $fjor;

	public function __construct(Fjor $fjor)
	{
		$this->fjor = $fjor;
	}

	public function create($patternClass)
	{
		return $this->fjor->get($patternClass);
	}
}