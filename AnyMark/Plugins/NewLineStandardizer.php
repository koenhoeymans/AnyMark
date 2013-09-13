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
class NewLineStandardizer implements Plugin
{
	public function register(EventMapper $mapper)
	{
		$mapper->registerForEvent(
			'BeforeParsingEvent', function(BeforeParsingEvent $event) {
				$event->setText(preg_replace("#\r\n?#", "\n", $event->getText()));
			}
		);
	}
}