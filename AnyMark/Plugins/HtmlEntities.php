<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Plugins;

use Epa\Plugin;
use Epa\EventMapper;
use ElementTree\ElementTree;
use ElementTree\Component;
use AnyMark\PublicApi\AfterParsingEvent;

/**
 * @package AnyMark
 */
class HtmlEntities implements Plugin
{
	public function register(EventMapper $mapper)
	{
		$mapper->registerForEvent(
			'AfterParsingEvent', function(AfterParsingEvent $event) {
				$this->handleTree($event->getTree());
			}
		);
	}
	
	private function handleTree(ElementTree $tree)
	{
		$tree->query(function(Component $component) {
			if ($component instanceof \ElementTree\Text)
			{
				$value = htmlentities($component->getValue(), ENT_NOQUOTES, 'UTF-8', false);
				$component->setValue($value);
			}
			elseif ($component instanceof \ElementTree\Element)
			{
				foreach ($component->getAttributes() as $attr)
				{
					$value = htmlentities($attr->getValue(), ENT_COMPAT, 'UTF-8', false);
					$attr->setValue($value);
				}
			}
		});
	}
}