<?php

/**
 * @package AnyMark
 */
namespace AnyMark\EndToEndTests\Support;

use AnyMark\Events\PatternConfigFile;
use Epa\EventMapper;
use Epa\Plugin;

/**
 * @package AnyMark
 */
class CustomPatternFilePlugin implements Plugin
{
	public function register(EventMapper $mapper)
	{
		$configFile = __DIR__ . DIRECTORY_SEPARATOR . 'CustomPatterns.php';
		$mapper->registerForEvent(
			'AnyMark\\Events\\PatternConfigFile',
			function(PatternConfigFile $event) use ($configFile) {
				$event->setPatternConfigFile($configFile);
			}
		);
	}
}