<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Events;

use Epa\Event;
use AnyMark\Pattern\PatternConfigDsl\Add;

/**
 * @package AnyMark
 */
class PatternConfigLoaded implements Event
{
	private $patternConfig;

	public function __construct(Add $patternConfig)
	{
		$this->patternConfig = $patternConfig;
	}

	public function getPatternConfig()
	{
		return $this->patternConfig;
	}
}