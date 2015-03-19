<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Plugins\LinkDefinitionCollector;

use AnyMark\PublicApi\BeforeParsingEvent;
use Epa\Api\EventDispatcher;
use Epa\Api\Plugin;

/**
 * @package AnyMark
 */
class LinkDefinitionCollectorRegistrar implements Plugin
{
	private $collector;

	public function __construct(LinkDefinitionCollector $collector)
	{
		$this->collector = $collector;
	}

	public function registerHandlers(EventDispatcher $eventDispatcher)
	{
		$collector = $this->collector;
		$eventDispatcher->registerForEvent(
			'AnyMark\\PublicApi\\BeforeParsingEvent', function(BeforeParsingEvent $event) use ($collector) {
				$event->setText($collector->process($event->getText()));
			}
		);
	}
}