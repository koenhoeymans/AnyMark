<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Plugins;

use AnyMark\Events\BeforeParsing;
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
			'AnyMark\\Events\\BeforeParsing', function(BeforeParsing $event) {
				$event->setText(preg_replace("#\r\n?#", "\n", $event->getText()));
			}
		);
	}
}