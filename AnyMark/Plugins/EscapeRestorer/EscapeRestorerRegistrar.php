<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Plugins\EscapeRestorer;

use AnyMark\PublicApi\AfterParsingEvent;
use AnyMark\PublicApi\PatternMatch;
use Epa\EventMapper;
use Epa\Plugin;

/**
 * @package AnyMark
 */
class EscapeRestorerRegistrar implements Plugin
{
	public function register(EventMapper $mapper)
	{
		$restorer = new \AnyMark\Plugins\EscapeRestorer\EscapeRestorer();
		$mapper->registerForEvent(
			'AfterParsingEvent', function(AfterParsingEvent $event) use ($restorer) {
				$restorer->restoreTree($event->getTree());
			}
		);
		$mapper->registerForEvent(
			'PatternMatch', function(PatternMatch $match) use ($restorer) {
				$restorer->handlePatternMatch($match);
			}
		);
	}
}