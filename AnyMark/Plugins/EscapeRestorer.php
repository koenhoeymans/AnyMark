<?php

/**
 * @package AnyMark
 */
namespace AnyMark\Plugins;

use ElementTree\Component;
use ElementTree\ElementTree;
use AnyMark\PublicApi\AfterParsingEvent;
use Epa\EventMapper;
use Epa\Plugin;

/**
 * @package AnyMark
 */
class EscapeRestorer implements Plugin
{
	public function register(EventMapper $mapper)
	{
		$mapper->registerForEvent(
			'AfterParsingEvent', function(AfterParsingEvent $event) {
				$this->restoreTree($event->getTree());
			}
		);
	}

	private function restoreTree(ElementTree $tree)
	{
		$filter = $tree->createFilter(function (Component $component)
		{
			$component->setValue($this->restoreText($component->getValue()));
		});

		$tree->query(
			$filter->lOr(
				$filter->lAnd(
					$filter->allText(),
					$filter->not($filter->hasParentElement('code'))
				),
				$filter->allAttributes()
			)
		);
	}

	private function restoreText($text)
	{
		return preg_replace(
			'@\\\\([\\\\`*_{}\[\]()>#+-.!])@', "\${1}", $text
		);
	}
}