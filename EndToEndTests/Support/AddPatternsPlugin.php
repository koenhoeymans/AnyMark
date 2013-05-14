<?php

/**
 * @package AnyMark
 */
namespace AnyMark\EndToEndTests\Support;

use AnyMark\PublicApi\EditPatternConfigurationEvent;
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
				$this->addPatterns($event);
			}
		);
	}

	public function addPatterns(EditPatternConfigurationEvent $patternConfig)
	{
		$patternConfig->setImplementation('italic', 'AnyMark\\Pattern\\Patterns\\Italic');
		$patternConfig->setImplementation('foo', 'AnyMark\\EndToEndTests\\Support\\Patterns\\FooChange');
		$patternConfig->add('italic')->toParent('root')->first();
		$patternConfig->add('foo')->toParent('italic')->first(); 
	}
}