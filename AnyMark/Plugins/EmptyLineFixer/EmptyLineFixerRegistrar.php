<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Plugins\EmptyLineFixer;

use AnyMark\PublicApi\BeforeParsingEvent;
use Epa\EventMapper;
use Epa\Plugin;

/**
 * @package AnyMark
 */
class EmptyLineFixerRegistrar implements Plugin
{
	public function register(EventMapper $mapper)
	{
		$fixer = new \AnyMark\Plugins\EmptyLineFixer\EmptyLineFixer();
		$mapper->registerForEvent(
			'BeforeParsingEvent', function(BeforeParsingEvent $event) use ($fixer) {
				$event->setText($fixer->fix($event->getText()));
			}
		);
	}
}