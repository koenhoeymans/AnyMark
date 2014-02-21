<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Plugins\HtmlEntities;

use Epa\Plugin;
use Epa\EventMapper;
use AnyMark\PublicApi\AfterParsingEvent;

/**
 * @package AnyMark
 */
class HtmlEntitiesRegistrar implements Plugin
{
	public function register(EventMapper $mapper)
	{
		$htmlEntities = new \AnyMark\Plugins\HtmlEntities\HtmlEntities();
		$mapper->registerForEvent(
			'AfterParsingEvent', function(AfterParsingEvent $event) use ($htmlEntities) {
				$htmlEntities->handleTree($event->getTree());
			}
		);
	}
}