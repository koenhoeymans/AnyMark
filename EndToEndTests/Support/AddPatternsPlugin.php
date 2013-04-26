<?php

/**
 * @package AnyMark
 */
namespace AnyMark\EndToEndTests\Support;

use AnyMark\Pattern\PatternConfigDsl\Add;
use Epa\EventMapper;
use Epa\Plugin;
use AnyMark\Events\PatternConfigLoaded;
use AnyMark\Events\PatternConfigFile;

/**
 * @package
 */
class AddPatternsPlugin implements Plugin
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
		$mapper->registerForEvent(
			'AnyMark\\Events\\PatternConfigLoaded',
			function(PatternConfigLoaded $event) {
				$this->addPatterns($event->getPatternConfig());
			}
		);
	}

	public function addPatterns(Add $patternConfig)
	{
		$patternConfig
			->add('italic', 'AnyMark\\Pattern\\Patterns\\Italic')
			->to('root')
			->first();
		$patternConfig
			->add('AnyMark\\EndToEndTests\\Support\\Patterns\\FooChange')
			->to('italic')
			->first(); 
	}
}