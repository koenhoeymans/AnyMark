<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Plugins\NewLineStandardizer;

use AnyMark\PublicApi\BeforeParsingEvent;
use Epa\EventMapper;
use Epa\Plugin;

/**
 * @package AnyMark
 */
class NewLineStandardizerRegistrar implements Plugin
{
	public function register(EventMapper $mapper)
	{
		$standardizer = new \AnyMark\Plugins\NewLineStandardizer\NewLineStandardizer();
		$mapper->registerForEvent(
			'BeforeParsingEvent', function(BeforeParsingEvent $event) use ($standardizer) {
				$event->setText($standardizer->replace($event->getText()));
			}
		);
	}
}