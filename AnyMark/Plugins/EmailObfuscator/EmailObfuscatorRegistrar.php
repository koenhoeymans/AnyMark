<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Plugins\EmailObfuscator;

use AnyMark\PublicApi\AfterParsingEvent;
use Epa\EventMapper;
use Epa\Plugin;

/**
 * @package AnyMark
 */
class EmailObfuscatorRegistrar implements Plugin
{
	public function register(EventMapper $mapper)
	{
		$obfuscator = new \AnyMark\Plugins\EmailObfuscator\EmailObfuscator();
		$mapper->registerForEvent(
			'AfterParsingEvent',
			function(AfterParsingEvent $event) use ($obfuscator)
			{
				$obfuscator->handleTree($event->getTree());
			}
		);
	}
}