<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Events;

use AnyMark\Pattern\Pattern;
use ElementTree\Component;
use ElementTree\ElementTree;
use Epa\Event;
use AnyMark\PublicApi\PatternMatch;

/**
 * @package AnyMark
 */
class ParsingPatternMatch implements Event, PatternMatch
{
	private $component;

	private $pattern;

	public function __construct(Component $component, Pattern $pattern)
	{
		$this->component = $component;
		$this->pattern = $pattern;
	}

	/**
	 * @see \AnyMark\PublicApi\PatternMatch::getComponent()
	 */
	public function getComponent()
	{
		return $this->component;
	}

	/**
	 * @see \AnyMark\PublicApi\PatternMatch::getPattern()
	 */
	public function getPattern()
	{
		return $this->pattern;
	}
}