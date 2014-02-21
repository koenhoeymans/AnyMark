<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Plugins\Detab;

use AnyMark\PublicApi\BeforeParsingEvent;
use Epa\EventMapper;
use Epa\Plugin;

/**
 * @package AnyMark
 */
class DetabRegistrar implements Plugin
{
	public function register(EventMapper $mapper)
	{
		$detab = new \AnyMark\Plugins\Detab\Detab();
		$mapper->registerForEvent(
			'BeforeParsingEvent', function(BeforeParsingEvent $event) use($detab) {
				$event->setText($detab->detab($event->getText()));
			}
		);
	}
}