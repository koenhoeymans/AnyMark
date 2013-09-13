<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Plugins;

use AnyMark\PublicApi\BeforeParsingEvent;
use Epa\EventMapper;
use Epa\Plugin;

/**
 * @package AnyMark
 */
class EmptyLineFixer implements Plugin
{
	public function register(EventMapper $mapper)
	{
		$mapper->registerForEvent(
			'BeforeParsingEvent', function(BeforeParsingEvent $event) {
				$event->setText(preg_replace("#\n[\t ]+\n#", "\n\n", $event->getText()));
			}
		);
	}
}