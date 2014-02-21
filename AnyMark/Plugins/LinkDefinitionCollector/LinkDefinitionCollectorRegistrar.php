<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Plugins\LinkDefinitionCollector;

use AnyMark\PublicApi\BeforeParsingEvent;
use Epa\EventMapper;
use Epa\Plugin;

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

	public function register(EventMapper $mapper)
	{
		$collector = $this->collector;
		$mapper->registerForEvent(
			'BeforeParsingEvent', function(BeforeParsingEvent $event) use ($collector) {
				$event->setText($collector->process($event->getText()));
			}
		);
	}
}