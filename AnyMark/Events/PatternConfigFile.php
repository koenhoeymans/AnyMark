<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Events;

use Epa\Event;
use AnyMark\Pattern\FileArrayPatternConfig;

/**
 * @package AnyMark
 */
class PatternConfigFile implements Event
{
	private $patternConfig;

	public function __construct(FileArrayPatternConfig $patternConfig)
	{
		$this->patternConfig = $patternConfig;
	}

	public function setPatternConfigFile($file)
	{
		$this->patternConfig->fillFrom($file);
	}
}